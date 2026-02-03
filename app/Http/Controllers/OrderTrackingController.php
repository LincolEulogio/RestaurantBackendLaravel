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
            ->with(['items.product', 'statusHistory'])
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
                'customer_last_name' => $order->customer_lastname,
                'total' => (float) $order->total,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'estimated_delivery' => $estimatedDelivery,
                'delivery_address' => $order->delivery_address,
                'order_type' => $order->order_type,
                'notes' => $order->notes,
                'timeline' => $timeline,
                'items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->product->name ?? $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->unit_price,
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
        // All possible statuses
        $allStatuses = [
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'preparing' => 'En Preparación',
            'ready' => 'Listo',
            'in_transit' => 'En Camino',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];

        $timeline = [];

        // Get status history notes
        $statusNotes = [];
        foreach ($order->statusHistory as $history) {
            if ($history->notes) {
                $statusNotes[$history->to_status] = $history->notes;
            }
        }

        // If order is cancelled, show only the path to cancellation
        if ($order->status === 'cancelled') {
            // Show all statuses up to where it was cancelled
            foreach ($allStatuses as $status => $label) {
                if ($status === 'cancelled') {
                    $timeline[] = [
                        'status' => $status,
                        'label' => $label,
                        'timestamp' => $order->updated_at->format('Y-m-d H:i:s'),
                        'completed' => true,
                        'current' => true,
                        'notes' => $statusNotes[$status] ?? null,
                    ];
                    break;
                }

                // Check if this status was reached before cancellation
                $wasReached = $this->wasStatusReached($order, $status);
                
                $timeline[] = [
                    'status' => $status,
                    'label' => $label,
                    'timestamp' => $wasReached ? $this->getStatusTimestamp($order, $status) : null,
                    'completed' => $wasReached,
                    'current' => false,
                    'notes' => $statusNotes[$status] ?? null,
                ];
            }

            return $timeline;
        }

        // Normal flow (not cancelled)
        $currentStatusReached = false;
        $statusOrder = ['pending', 'confirmed', 'preparing'];

        // Add ready or in_transit based on order type
        if ($order->order_type === 'delivery') {
            $statusOrder[] = 'in_transit';
        } else {
            $statusOrder[] = 'ready';
        }

        $statusOrder[] = 'delivered';

        foreach ($statusOrder as $status) {
            $isCompleted = !$currentStatusReached;
            $isCurrent = $order->status === $status;

            if ($isCurrent) {
                $currentStatusReached = true;
            }

            // Get timestamp from status-specific field
            $timestamp = null;
            if ($isCompleted || $isCurrent) {
                $timestamp = $this->getStatusTimestamp($order, $status);
            }

            $timeline[] = [
                'status' => $status,
                'label' => $allStatuses[$status],
                'timestamp' => $timestamp,
                'completed' => $isCompleted,
                'current' => $isCurrent,
                'notes' => $statusNotes[$status] ?? null,
            ];
        }

        return $timeline;
    }

    /**
     * Check if a status was reached before cancellation
     */
    private function wasStatusReached(Order $order, string $status): bool
    {
        $statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered'];
        $currentIndex = array_search($order->status, $statusOrder);
        $checkIndex = array_search($status, $statusOrder);

        if ($currentIndex === false || $checkIndex === false) {
            return false;
        }

        return $checkIndex <= $currentIndex;
    }

    /**
     * Get timestamp for a specific status
     */
    private function getStatusTimestamp(Order $order, string $status): ?string
    {
        $timestamp = match($status) {
            'pending' => $order->created_at,
            'confirmed' => $order->confirmed_at,
            'ready' => $order->ready_at,
            'in_transit' => $order->in_transit_at,
            'delivered' => $order->delivered_at,
            'cancelled' => $order->updated_at,
            default => null,
        };

        return $timestamp ? $timestamp->format('Y-m-d H:i:s') : null;
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
