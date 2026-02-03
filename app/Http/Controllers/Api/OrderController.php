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
use App\Services\PrintService;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function __construct(protected PrintService $printService) {}
    /**
     * Display a listing of orders.
     * 
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Get all orders",
     *     description="Returns paginated list of orders (requires authentication)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending","confirmed","preparing","ready","delivered","cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by order number, customer name or phone",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created order.
     * 
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     description="Creates a new order with items (no authentication required for public orders)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_name","customer_phone","order_type","items"},
     *             @OA\Property(property="customer_name", type="string", example="Juan"),
     *             @OA\Property(property="customer_lastname", type="string", example="Pérez"),
     *             @OA\Property(property="customer_email", type="string", example="juan@example.com"),
     *             @OA\Property(property="customer_phone", type="string", example="987654321"),
     *             @OA\Property(property="delivery_address", type="string", example="Av. Principal 123"),
     *             @OA\Property(property="order_type", type="string", enum={"delivery","pickup","dine-in","online"}),
     *             @OA\Property(property="payment_method", type="string", enum={"card","yape","plin","cash"}),
     *             @OA\Property(property="notes", type="string", example="Sin cebolla"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="special_instructions", type="string", example="Extra queso")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pedido creado correctamente"),
     *             @OA\Property(property="order", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
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

            // Create order (public API = online/web)
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
                'payment_status' => 'pending',
                'order_source' => 'online',
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

            try {
                // Load relationships for response
                $order->load(['items.product', 'statusHistory', 'table', 'waiter']);

                // Emit WebSocket Event
                event(new \App\Events\OrderPlaced($order));

                // Print Ticket
                $this->printService->printOrderTicket($order);

                // Send notification to users with 'orders' permission
                // Optimization: Use a more direct query if possible, 
                // but for now let's keep it safe with a smaller set if there are many users
                $usersToNotify = User::whereIn('role', ['admin', 'superadmin'])->get();
                
                if ($usersToNotify->count() > 0) {
                    try {
                        Notification::send($usersToNotify, new NewOrderAlert($order));
                    } catch (\Throwable $notifEx) {
                        \Log::error('Notification failed: ' . $notifEx->getMessage());
                    }
                }
            } catch (\Throwable $e) {
                // Log error but don't fail the request since the order was already created and committed
                \Log::error('Post-creation error in OrderController: '.$e->getMessage(), [
                    'order_id' => $order->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return response()->json([
                'message' => 'Pedido creado correctamente',
                'order' => new OrderResource($order),
            ], 201);

        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            $errorDetail = [
                'endpoint' => 'api/orders',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];

            // Log full trace but keep response small
            \Log::error('Order creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            @file_put_contents(public_path('debug_log.json'), json_encode(array_merge($errorDetail, ['trace' => $e->getTraceAsString()]), JSON_PRETTY_PRINT));

            return response()->json([
                'message' => 'No se pudo crear el pedido',
                'error' => $e->getMessage(),
                'detail' => $errorDetail
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
