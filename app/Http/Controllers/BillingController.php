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

        // Base query - Only show orders that are 'ready' or need a bill
        $query = Order::with(['items.product', 'table'])
            ->where('status', 'ready');

        // Logic: Online orders that are already PAID (Card) should NOT show up here
        // as they are already processed automatically.
        // We only show:
        // 1. All Waiter/QR orders (needs payment)
        // 2. Online orders that are PENDING payment (Cash, Yape, Plin verification)
        $query->where(function ($q) {
            $q->whereIn('order_source', ['waiter', 'qr', 'web_pos']) // In-person sources
              ->orWhere(function ($sub) {
                  $sub->whereIn('order_source', ['web', 'online'])
                      ->where('payment_status', '!=', 'paid'); // Online but still needs money or verification
              });
        });

        // Contextual Filter based on Role (Keep restrictions if any)
        $user = auth()->user();
        if ($user->hasRole('cashier')) {
            $query->whereNotIn('order_source', ['web', 'online']);
        } elseif ($user->hasRole('delivery')) {
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
            'payment_method' => 'required|in:cash,card,yape,plin,transfer,online',
            'amount_received' => 'required|numeric|min:0',
        ]);

        // Verify order is ready for payment
        if ($order->status !== 'ready') {
            return back()->with('error', 'Este pedido no está listo para cobrar');
        }

        // Verify amount received is sufficient
        if ($request->amount_received < $order->total) {
            return back()->with('error', 'El monto recibido es insuficiente');
        }

        // Update order status and payment status using the centralized model method
        DB::beginTransaction();
        try {
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

            // Calculate change
            $change = $request->amount_received - $order->total;

            return redirect()->route('billing.index')
                ->with('success', "Pago procesado exitosamente. Comprobante {$invoice->invoice_number} generado.")
                ->with('change', $change);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
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
