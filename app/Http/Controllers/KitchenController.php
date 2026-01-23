<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class KitchenController extends Controller
{
    /**
     * Display the kitchen KDS view with active orders.
     */
    public function index()
    {
        // SELF-HEAL: Ensure inventory tables exist (fixes 500 error if migration failed)
        if (!Schema::hasTable('product_inventory')) {
            try {
                if (!Schema::hasTable('inventory_items')) {
                    Schema::create('inventory_items', function (Blueprint $table) {
                        $table->id();
                        $table->string('name');
                        $table->string('unit')->default('unit');
                        $table->decimal('stock_current', 10, 2)->default(0);
                        $table->decimal('stock_min', 10, 2)->default(0);
                        $table->timestamps();
                    });
                }
                Schema::create('product_inventory', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('product_id');
                    $table->unsignedBigInteger('inventory_item_id');
                    $table->decimal('quantity', 10, 2)->default(1);
                    $table->timestamps();
                    $table->index('product_id');
                    $table->index('inventory_item_id');
                });
            } catch (\Exception $e) {
                \Log::error("Failed to self-heal product_inventory table: " . $e->getMessage());
            }
        }

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
     * Fetch orders only (for AJAX refresh).
     */
    public function fetchOrders()
    {
        $orders = Order::with(['items.product'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        $pendingCount = $orders->where('status', 'pending')->count();
        $preparingCount = $orders->whereIn('status', ['confirmed', 'preparing'])->count();
        $readyCount = $orders->where('status', 'ready')->count();

        return view('kitchen.orders-partial', compact('orders', 'pendingCount', 'preparingCount', 'readyCount'));
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
