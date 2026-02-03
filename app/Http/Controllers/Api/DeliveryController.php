<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    /**
     * Display a listing of delivery orders.
     * 
     * Returns orders that are delivery type and from online source.
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'deliveryPayment'])
            ->where('order_type', 'delivery')
            ->whereIn('order_source', ['online', 'web'])
            ->orderBy('created_at', 'desc');

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return response()->json($orders);
    }

    /**
     * Display the specified delivery order.
     */
    public function show(Order $order)
    {
        // Verify this is a delivery order
        if ($order->order_type !== 'delivery') {
            return response()->json([
                'message' => 'Esta orden no es de tipo delivery',
            ], 403);
        }

        $order->load(['items.product', 'deliveryPayment', 'statusHistory.user']);

        return response()->json($order);
    }

    /**
     * Confirm cash payment received by delivery person.
     */
    public function confirmPayment(Request $request, Order $order)
    {
        // Validate order is delivery and payment method is cash
        if ($order->order_type !== 'delivery') {
            return response()->json([
                'message' => 'Esta orden no es de tipo delivery',
            ], 403);
        }

        if ($order->payment_method !== 'cash') {
            return response()->json([
                'message' => 'Esta orden no tiene método de pago en efectivo',
            ], 400);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'message' => 'Esta orden ya ha sido pagada',
            ], 400);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'amount_received' => 'required|numeric|min:0',
            'change_given' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de pago inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Create delivery payment record
            $deliveryPayment = DeliveryPayment::create([
                'order_id' => $order->id,
                'delivery_user_id' => auth()->id(),
                'amount_received' => $request->amount_received,
                'change_given' => $request->change_given ?? 0,
                'notes' => $request->notes,
            ]);

            // Mark order as paid
            $order->markAsPaid();

            // Load relationships for response
            $order->load(['deliveryPayment', 'items.product']);

            return response()->json([
                'message' => 'Pago confirmado exitosamente',
                'order' => $order,
                'delivery_payment' => $deliveryPayment,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo confirmar el pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
