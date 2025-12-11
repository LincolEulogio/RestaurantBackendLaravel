<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display the kitchen KDS view with active orders.
     */
    public function index()
    {
        // Get orders that are relevant for the kitchen (not delivered or cancelled)
        $orders = Order::with(['items.product'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Count orders by status
        $pendingCount = $orders->where('status', 'pending')->count();
        $preparingCount = $orders->whereIn('status', ['confirmed', 'preparing'])->count();
        $readyCount = $orders->where('status', 'ready')->count();

        return view('kitchen.index', compact('orders', 'pendingCount', 'preparingCount', 'readyCount'));
    }

    /**
     * Update order status from kitchen.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready',
        ]);

        $userId = auth()->id();
        $order->updateStatus($request->status, $userId);

        return redirect()->route('kitchen.index')
            ->with('success', 'Estado del pedido actualizado');
    }
}
