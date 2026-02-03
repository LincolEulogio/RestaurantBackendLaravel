<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderTrackingController extends Controller
{
    /**
     * Track an order by its unique code
     *
     * @param string $orderCode
     * @return JsonResponse
     */
    public function track(string $orderCode): JsonResponse
    {
        // Find order by number
        $order = Order::where('order_number', $orderCode)
            ->with(['items.product'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado. Verifica el código e intenta nuevamente.'
            ], 404);
        }

        // Build timeline based on order status
        $timeline = $this->buildTimeline($order);

        // Calculate estimated delivery time
        $estimatedDelivery = $this->calculateEstimatedDelivery($order);

        return response()->json([
            'success' => true,
            'order' => [
                'code' => $order->order_number,
                'status' => $order->status,
                'customer_name' => $order->customer_name,
                'total' => (float) $order->total,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'estimated_delivery' => $estimatedDelivery,
                'delivery_address' => $order->delivery_address,
                'order_type' => $order->order_type,
                'timeline' => $timeline,
                'items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->product->name ?? $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Build timeline based on order status
     *
     * @param Order $order
     * @return array
     */
    private function buildTimeline(Order $order): array
    {
        $statuses = [
            'pending' => 'Pedido Recibido',
            'confirmed' => 'Confirmado',
            'preparing' => 'En Preparación',
            'ready' => 'Listo',
            'in_transit' => 'En Camino',
            'delivered' => 'Entregado',
        ];

        $timeline = [];
        $currentStatusReached = false;

        foreach ($statuses as $status => $label) {
            $isCompleted = !$currentStatusReached;
            $isCurrent = $order->status === $status;

            if ($isCurrent) {
                $currentStatusReached = true;
            }

            // Skip "in_transit" for non-delivery orders
            if ($status === 'in_transit' && $order->order_type !== 'delivery') {
                continue;
            }

            // Skip "ready" for delivery orders (goes straight to in_transit)
            if ($status === 'ready' && $order->order_type === 'delivery') {
                continue;
            }

            $timeline[] = [
                'status' => $status,
                'label' => $label,
                'timestamp' => $isCompleted || $isCurrent ? $order->updated_at->format('Y-m-d H:i:s') : null,
                'completed' => $isCompleted,
                'current' => $isCurrent,
            ];
        }

        return $timeline;
    }

    /**
     * Calculate estimated delivery time
     *
     * @param Order $order
     * @return string|null
     */
    private function calculateEstimatedDelivery(Order $order): ?string
    {
        if ($order->status === 'delivered') {
            return null;
        }

        // Base preparation time: 30 minutes
        $preparationMinutes = 30;

        // Add delivery time if it's a delivery order
        if ($order->order_type === 'delivery') {
            $preparationMinutes += 20; // 20 minutes for delivery
        }

        // Calculate from order creation time
        $estimatedTime = $order->created_at->copy()->addMinutes($preparationMinutes);

        return $estimatedTime->format('Y-m-d H:i:s');
    }
}
