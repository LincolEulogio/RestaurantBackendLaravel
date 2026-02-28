<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    /**
     * Display billing page with ready orders.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $dateFilter = $request->input('date_filter', 'today');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $orderTypeFilter = $request->input('order_type_filter', 'all');

        // Base query - Show:
        // 1. Orders that are 'ready' (Kitchen finished them, needs collection/payment)
        // 2. Web orders that are 'pending' or 'confirmed' but NOT yet paid (Needs verification BEFORE kitchen)
        $query = Order::with(['items.product', 'table'])
            ->where(function($q) {
                // 1. Ready orders that still need to be PAID (Mental: in-person orders)
                $q->where('status', 'ready')
                  ->where('payment_status', '!=', 'paid')
                  // 2. Web orders waiting for initial verification
                  ->orWhere(function($sub) {
                      $sub->whereIn('order_source', ['web', 'online'])
                          ->where('payment_status', '!=', 'paid')
                          ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
                  });
            });

        // Contextual Filter based on Role:
        // Cashiers should see EVERYTHING that needs verification or is ready to be closed.
        // Delivery should only see Web/Online orders that are Ready.
        $user = auth()->user();
        if ($user->hasRole('delivery')) {
            $query->whereIn('order_source', ['web', 'online']);
        }

        // Apply Date Filters
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', today()->subDay());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'custom':
                if ($dateFrom && $dateTo) {
                    $query->whereBetween('created_at', [
                        \Carbon\Carbon::parse($dateFrom)->startOfDay(),
                        \Carbon\Carbon::parse($dateTo)->endOfDay()
                    ]);
                } elseif ($dateFrom) {
                    $query->whereDate('created_at', '>=', $dateFrom);
                } elseif ($dateTo) {
                    $query->whereDate('created_at', '<=', $dateTo);
                }
                break;
        }

        // Apply Order Type Filters
        switch ($orderTypeFilter) {
            case 'delivery':
                $query->where('order_type', 'delivery');
                break;
            case 'waiter':
                $query->where(function ($q) {
                    $q->where('order_source', 'waiter')
                        ->orWhere('order_type', 'dine_in');
                });
                break;
            case 'all':
            default:
                // No additional filter
                break;
        }

        // Sort by Payment Status (Pending first) -> Then by Time
        $readyOrders = $query->orderByRaw("CASE payment_status 
            WHEN 'pending' THEN 1 
            WHEN 'failed' THEN 2 
            WHEN 'paid' THEN 3 
            ELSE 4 
        END")
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate statistics based on filtered results
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        $todayRevenue = Order::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->sum('total');
        $pendingPayments = $readyOrders->where('payment_status', 'pending')->sum('total');

        return view('billing.index', compact(
            'readyOrders',
            'totalRevenue',
            'todayRevenue',
            'pendingPayments',
            'dateFilter',
            'dateFrom',
            'dateTo',
            'orderTypeFilter'
        ));
    }

    /**
     * Process payment for an order.
     */
    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required',
            'amount_received' => 'nullable|numeric|min:0',
        ]);

        // Logic: Process Payment / Verification
        DB::beginTransaction();
        try {
            // WEB ORDER LOGIC: Direct verification BEFORE kitchen
            if (in_array($order->order_source, ['web', 'online']) && $order->payment_status !== 'paid') {
                // Generate Invoice first (but keeping status under control)
                $order->billing_type = $order->billing_type ?: 'boleta';
                $invoiceType = $order->billing_type;
                $prefix = ($invoiceType === 'factura' ? 'F' : 'B');
                $count = Invoice::where('invoice_type', $invoiceType)->count() + 1;
                $invoiceNumber = $prefix . sprintf('%03d', 1) . '-' . sprintf('%06d', $count);

                $invoice = Invoice::create([
                    'order_id' => $order->id,
                    'invoice_number' => $invoiceNumber,
                    'invoice_type' => $invoiceType,
                    'customer_name' => $order->customer_name . ' ' . $order->customer_lastname,
                    'customer_document_type' => $invoiceType === 'factura' ? 'RUC' : 'DNI',
                    'customer_document_number' => $invoiceType === 'factura' ? $order->ruc : $order->customer_dni,
                    'customer_address' => $order->fiscal_address ?: $order->delivery_address,
                    'subtotal' => $order->subtotal,
                    'tax' => $order->tax,
                    'total' => $order->total,
                    'status' => 'issued',
                ]);

                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => 'confirmed' // Send to Kitchen
                ]);
                
                // Track status history
                $order->statusHistory()->create([
                    'from_status' => 'pending',
                    'to_status' => 'confirmed',
                    'user_id' => auth()->id(),
                    'notes' => "Pago web verificado. Boleta {$invoice->invoice_number} generada. Enviado a cocina."
                ]);

                DB::commit();
                return redirect()->route('billing.index')->with('success', "Pago verificado. Boleta {$invoice->invoice_number} generada y pedido enviado a cocina.");
            }

            // REGULAR ORDER LOGIC: Collection of 'ready' orders
            $invoice = $order->finalizeAndInvoice();

            // Automation: Release table and close session if it's a dine-in/waiter order
            if ($order->table_id && $order->table) {
                $table = $order->table;
                
                if ($table->currentSession) {
                    $table->currentSession->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                }

                $table->update([
                    'status' => 'available',
                    'current_session_id' => null,
                ]);
            }

            DB::commit();

            // Calculate change safely
            $received = floatval($request->input('amount_received', $order->total));
            $total = floatval($order->total);
            
            // Logic: If it's a digital payment and $received is empty, assume full payment
            if ($request->payment_method !== 'cash' && (!$request->amount_received || floatval($request->amount_received) <= 0)) {
                $received = $total;
            }

            $change = max(0, $received - $total);

            return redirect()->route('billing.index')
                ->with('success', "Pago procesado exitosamente. Comprobante {$invoice->invoice_number} generado.")
                ->with('change', $change);

    } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Lookup RUC data (Simulated/Professional Integration)
     */
    public function lookupRuc($ruc)
    {
        if (strlen($ruc) !== 11) {
            return response()->json(['success' => false, 'message' => 'RUC debe tener 11 dígitos'], 400);
        }

        // Professional Simulation (Replace with actual Sunat API if available)
        // In production, we use Guzzle to query services like ApisPeru or Migo
        $mockData = [
            '20100018625' => ['business_name' => 'ESALUD', 'address' => 'AV. ARENALES NRO. 1402 LIMA - LIMA - JESUS MARIA'],
            '20100017491' => ['business_name' => 'SUNAT', 'address' => 'AV. GARCILASO DE LA VEGA NRO. 1472 LIMA - LIMA - LIMA'],
        ];

        if (isset($mockData[$ruc])) {
            return response()->json([
                'success' => true, 
                'data' => $mockData[$ruc]
            ]);
        }

        // Generic mock for unknown RUCs
        return response()->json([
            'success' => true,
            'data' => [
                'business_name' => 'EMPRESA CONSULTADA SAC',
                'address' => 'AV. PRINCIPAL 123 - LIMA'
            ]
        ]);
    }

    /**
     * Reject or report an issue with the payment.
     */
    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            
            // Append reason to notes
            $newNotes = ($order->notes ? $order->notes . "\n" : "") . "--- PAGO RECHAZADO ---\nMotivo: " . $request->reason;
            
            $order->update([
                'payment_status' => 'failed',
                'notes' => $newNotes
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Problema de pago reportado']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Download or view the current invoice as PDF.
     */
    public function downloadInvoice(Order $order)
    {
        $invoice = Invoice::where('order_id', $order->id)->first();
        if (!$invoice) {
            return back()->with('error', 'No se ha generado un comprobante para este pedido aún.');
        }

        $order->load(['items.product']);
        $settings = Setting::pluck('value', 'key')->all();

        $pdf = Pdf::loadView('billing.invoice_pdf', [
            'invoice_type' => $invoice->invoice_type,
            'invoice_number' => $invoice->invoice_number,
            'date' => $invoice->created_at->format('d/m/Y H:i'),
            'customer_name' => $invoice->customer_name,
            'customer_document' => $invoice->customer_document_number,
            'customer_address' => $invoice->customer_address,
            'items' => $order->items,
            'subtotal' => $invoice->subtotal,
            'tax' => $order->tax ?: ($order->total / 1.18 * 0.18),
            'total' => $invoice->total,
            'settings' => $settings,
        ]);

        return $pdf->stream("{$invoice->invoice_number}.pdf");
    }

    /**
     * Get order details for payment.
     */
    public function getOrderDetails(Order $order)
    {
        $order->load(['items.product']);

        return response()->json([
            'order' => $order,
            'items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'notes' => $item->notes,
                ];
            }),
        ]);
    }
}
