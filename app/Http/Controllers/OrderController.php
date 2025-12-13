<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        // 1. Base Query with Role Restrictions
        $baseQuery = Order::query();
        $user = auth()->user();

        if ($user->hasRole('cashier')) {
            // Cashier: Sees everything EXCEPT Online orders
            $baseQuery->whereNotIn('order_source', ['web', 'online']);
        } elseif ($user->hasRole('delivery')) {
            // Delivery: Only sees orders from Web/Online
            $baseQuery->whereIn('order_source', ['web', 'online']);
        }

        // 2. Main List Query (Clone base to avoid polluting stats)
        $query = (clone $baseQuery)->with(['items.product'])->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
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

        $orders = $query->paginate(20);

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
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $userId = auth()->id();
        $order->updateStatus($request->status, $userId, $request->notes);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order status updated successfully');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully');
    }
}
