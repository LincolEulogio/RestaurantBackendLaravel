<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        // 1. Base Query with Role Restrictions
        $baseQuery = Order::query();
        $user = Auth::user();

        if ($user->role === 'cashier') {    
            // Cashier: Sees everything EXCEPT Online orders
            $baseQuery->whereNotIn('order_source', ['web', 'online']);
        } elseif ($user->role === 'delivery') {
            // Delivery: Only sees orders from Web/Online
            $baseQuery->whereIn('order_source', ['web', 'online']);
        }

        // 2. Main List Query (Clone base to avoid polluting stats)
        $query = (clone $baseQuery)->with(['items.product'])->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by order type
        if ($request->has('order_type') && $request->order_type !== 'all') {
            $query->where('order_type', $request->order_type);
        }

        // Filter by specific date
        if ($request->has('date') && $request->date) {
            $query->whereDate('order_date', $request->date);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->appends($request->except('page'));

        // 3. Statistics (Clone base for each to keep role filters)
        $totalOrders = (clone $baseQuery)->count();
        $pendingOrders = (clone $baseQuery)->where('status', 'pending')->count();
        $inProgressOrders = (clone $baseQuery)->whereIn('status', ['confirmed', 'preparing'])->count();
        $completedOrders = (clone $baseQuery)->whereIn('status', ['ready', 'delivered'])->count();
        $deliveredOrders = (clone $baseQuery)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $baseQuery)->where('status', 'cancelled')->count();

        return view('orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'inProgressOrders', 'completedOrders', 'deliveredOrders', 'cancelledOrders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'statusHistory.user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled,in_transit',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $userId = $user ? $user->getAuthIdentifier() : null;
        $order->updateStatus($request->status, $userId, $request->notes);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Estado del pedido actualizado correctamente');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pedido eliminado correctamente');
    }
}