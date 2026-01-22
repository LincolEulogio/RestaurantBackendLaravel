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

class QROrderController extends Controller
{
    /**
     * Verify QR token and get table info
     */
    public function checkTable($qrCode)
    {
        $table = Table::where('qr_code', $qrCode)->first();

        if (! $table) {
            return response()->json(['message' => 'Invalid QR Code'], 404);
        }

        return response()->json([
            'table' => $table->load('currentSession'),
            'status' => $table->status,
        ]);
    }

    /**
     * Store order from QR (Self-Service)
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

        try {
            DB::beginTransaction();

            // Ensure session exists or start one
            if (! $table->current_session_id) {
                // For QR, we might want to be more strict, but for now auto-start
                $session = TableSession::create([
                    'table_id' => $table->id,
                    'session_token' => $table->qr_code, // Or generate new unique session token
                    'status' => 'active',
                    'started_at' => now(),
                ]);

                $table->update([
                    'status' => 'occupied',
                    'current_session_id' => $session->id,
                ]);
                $table->refresh();
            }

            $order = Order::create([
                'table_id' => $table->id,
                'waiter_id' => null, // Self-service
                'order_source' => 'qr_self_service',
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
                    'special_instructions' => $item['notes'] ?? null,
                ]);

                $subtotal += $itemSubtotal;
            }

            $order->subtotal = $subtotal;
            $order->tax = $subtotal * 0.18;
            $order->total = $subtotal + $order->tax;
            $order->save();

            $table->currentSession->increment('total_amount', $order->total);

            DB::commit();

            // Emit WebSocket Event
            event(new \App\Events\OrderPlaced($order));

            return response()->json($order->load('items.product'), 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Error creating order: '.$e->getMessage()], 500);
        }
    }

    public function callWaiter(Request $request)
    {
        $request->validate(['table_id' => 'required|exists:tables,id']);

        // Logic to notify waiters (Event/Notification)
        // event(new WaiterCalled($request->table_id));

        return response()->json(['message' => 'Waiter has been notified']);
    }

    public function requestBill(Request $request)
    {
        $request->validate(['table_id' => 'required|exists:tables,id']);

        $table = Table::findOrFail($request->table_id);
        if ($table->currentSession) {
            // event(new BillRequested($table->id));
            return response()->json(['message' => 'Bill requested']);
        }

        return response()->json(['message' => 'No active session'], 400);
    }
}
