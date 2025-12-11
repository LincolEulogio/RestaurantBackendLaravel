<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display billing page with ready orders.
     */
    public function index()
    {
        // Get ALL ready orders (Paid and Unpaid) so cashier has full visibility
        // Sort by Payment Status (Pending first) -> Then by Time
        $readyOrders = Order::with(['items.product', 'waiter', 'table'])
            ->where('status', 'ready')
            ->orderByRaw("FIELD(payment_status, 'pending', 'failed', 'paid')")
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate statistics
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        $todayRevenue = Order::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->sum('total');
        $pendingPayments = Order::where('status', 'ready')->sum('total');

        return view('billing.index', compact('readyOrders', 'totalRevenue', 'todayRevenue', 'pendingPayments'));
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

        // Update order to delivered
        $userId = auth()->id();
        $order->updateStatus('delivered', $userId, "Pagado con {$request->payment_method}");

        // Calculate change
        $change = $request->amount_received - $order->total;

        return redirect()->route('billing.index')
            ->with('success', 'Pago procesado exitosamente')
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
