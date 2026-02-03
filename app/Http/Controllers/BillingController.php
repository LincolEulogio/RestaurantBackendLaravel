<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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

        // Base query
        $query = Order::with(['items.product', 'waiter', 'table'])
            ->where('status', 'ready');

        // Contextual Filter based on Role
        $user = auth()->user();

        if ($user->hasRole('cashier')) {
            // Cashier: Sees everything EXCEPT Online orders (so they see Waiter, QR, Null, etc.)
            $query->whereNotIn('order_source', ['web', 'online']);
        } elseif ($user->hasRole('delivery')) {
            // Delivery: Only sees orders from Web/Online
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

        // Update order status and payment status
        $userId = auth()->id();
        $order->payment_status = 'paid';
        $order->save();

        $order->updateStatus('delivered', $userId, "Pagado con {$request->payment_method}");

        // Automation: Release table and close session if it's a dine-in/waiter order
        if ($order->table_id && $order->table) {
            $table = $order->table;
            
            // Close session
            if ($table->currentSession) {
                $table->currentSession->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            // Free table
            $table->update([
                'status' => 'available',
                'current_session_id' => null,
            ]);
        }

        // Calculate change
        $change = $request->amount_received - $order->total;

        return redirect()->route('billing.index')
            ->with('success', 'Pago procesado exitosamente y mesa liberada')
            ->with('change', $change);
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
