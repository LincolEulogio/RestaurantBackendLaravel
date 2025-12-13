<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\TableSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WaiterOrderController extends Controller
{
    /**
     * Get all tables with their current status
     */
    public function tables()
    {
        $tables = Table::with(['currentSession', 'currentSession.orders' => function ($q) {
            $q->where('status', '!=', 'cancelled')->where('status', '!=', 'delivered');
        }])->get();

        return response()->json($tables);
    }

    /**
     * Update table status
     */
    public function updateTableStatus(Request $request, Table $table)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,reserved,cleaning,maintenance',
        ]);

        $table->update([
            'status' => $validated['status'],
        ]);

        // If making available, we might want to ensure there is no active session or close it?
        // For now, just updating status is enough as requested.
        // If the table is made 'available', the active session effectively becomes history
        // normally, but let's keep it simple and just update the status field.

        return response()->json($table);
    }

    /**
     * Start a new session for a table
     */
    public function startSession(Request $request, Table $table)
    {
        if ($table->status === 'occupied') {
            return response()->json(['message' => 'Table is already occupied'], 400);
        }

        $session = TableSession::create([
            'table_id' => $table->id,
            'session_token' => Str::random(32),
            'status' => 'active',
            'started_at' => now(),
            'waiter_id' => auth()->id(),
        ]);

        $table->update([
            'status' => 'occupied',
            'current_session_id' => $session->id,
        ]);

        return response()->json($session);
    }

    /**
     * Store a new order from waiter
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $table = Table::findOrFail($request->table_id);

        if (! $table->current_session_id) {
            // Auto-start session if not exists
            $this->startSession($request, $table);
            $table->refresh();
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'table_id' => $table->id,
                'waiter_id' => auth()->id(),
                'order_source' => 'waiter',
                'session_token' => $table->currentSession->session_token,
                'status' => 'pending',
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
                'order_date' => now(),
            ]);

            $subtotal = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemSubtotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                ]);

                $subtotal += $itemSubtotal;
            }

            $order->subtotal = $subtotal;
            $order->tax = 0;
            $order->total = $subtotal + $order->tax;
            $order->save();

            // Update session total
            $table->currentSession->increment('total_amount', $order->total);

            DB::commit();

            return response()->json($order->load('items.product'), 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Error creating order: '.$e->getMessage()], 500);
        }
    }

    /**
     * Get active orders for the current waiter
     */
    public function myOrders()
    {
        $orders = Order::where('waiter_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->with(['items.product', 'table'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}
