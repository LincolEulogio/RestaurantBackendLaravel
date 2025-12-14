<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'statusHistory.user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json($orders);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_lastname' => 'nullable|string|max:255',
            'customer_dni' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'nullable|string',
            'order_type' => 'required|in:delivery,pickup,dine-in,online',
            'payment_method' => 'nullable|in:card,yape,plin,cash',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.special_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_lastname' => $request->customer_lastname,
                'customer_dni' => $request->customer_dni,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'delivery_address' => $request->delivery_address,
                'order_type' => $request->order_type,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => 'pending',
                'subtotal' => 0,
                'tax' => 0,
                'delivery_fee' => 0,
                'total' => 0,
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            // Calculate totals
            $order->calculateTotal();

            // Record initial status
            $order->statusHistory()->create([
                'from_status' => '',
                'to_status' => 'pending',
                'notes' => 'Pedido creado',
            ]);

            DB::commit();

            // Load relationships for response
            $order->load(['items.product', 'statusHistory']);

            // Send notification to users with 'orders' permission (Admins, Kitchen, Waiters, etc.)
            try {
                // Filter users who have the 'orders' permission
                $usersToNotify = User::all()->filter(function ($user) {
                    return $user->hasPermission('orders');
                });

                if ($usersToNotify->count() > 0) {
                    Notification::send($usersToNotify, new NewOrderAlert($order));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to send notification: '.$e->getMessage());
            }

            return response()->json([
                'message' => 'Pedido creado correctamente',
                'order' => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'No se pudo crear el pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'statusHistory.user']);

        return response()->json($order);
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'No se pudo actualizar el estado del pedido',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $userId = auth()->id();
            $order->updateStatus($request->status, $userId, $request->notes);

            $order->load(['items.product', 'statusHistory.user']);

            return response()->json([
                'message' => 'Estado del pedido actualizado correctamente',
                'order' => $order,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el estado del pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();

            return response()->json([
                'message' => 'Pedido eliminado correctamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo eliminar el pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
