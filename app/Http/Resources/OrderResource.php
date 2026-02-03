<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->whenLoaded('items', function () {
            return $this->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->subtotal,
                'special_instructions' => $item->special_instructions,
                'notes' => $item->special_instructions,
            ]);
        });

        return array_merge(
            [
                // Flat fields for frontend compatibility (Order interface)
                'id' => $this->id,
                'order_number' => $this->order_number,
                'customer_name' => $this->customer_name,
                'customer_lastname' => $this->customer_lastname,
                'customer_dni' => $this->customer_dni,
                'customer_email' => $this->customer_email,
                'customer_phone' => $this->customer_phone,
                'delivery_address' => $this->delivery_address,
                'order_type' => $this->order_type,
                'status' => $this->status,
                'subtotal' => (float) $this->subtotal,
                'tax' => (float) $this->tax,
                'delivery_fee' => (float) $this->delivery_fee,
                'total' => (float) $this->total,
                'notes' => $this->notes,
                'created_at' => $this->created_at?->format('c'),
                'payment_status' => $this->payment_status ?? 'pending',
                'order_source' => $this->order_source ?? 'web',
            ],
            [
                'customer' => [
                    'name' => $this->customer_name,
                    'lastname' => $this->customer_lastname,
                    'dni' => $this->customer_dni,
                    'email' => $this->customer_email,
                    'phone' => $this->customer_phone,
                ],
                'type' => $this->order_type,
                'source' => $this->order_source ?? 'web',
                'status_object' => [
                    'code' => $this->status,
                    'label' => $this->status_label,
                    'badge' => $this->status_badge,
                ],
                'totals' => [
                    'subtotal' => (float) $this->subtotal,
                    'tax' => (float) $this->tax,
                    'delivery_fee' => (float) $this->delivery_fee,
                    'total' => (float) $this->total,
                ],
                'items' => $items,
                'table' => $this->whenLoaded('table'),
                'waiter' => $this->whenLoaded('waiter'),
                'dates' => [
                    'created' => $this->created_at,
                    'confirmed' => $this->confirmed_at,
                    'ready' => $this->ready_at,
                    'delivered' => $this->delivered_at,
                ],
            ]
        );
    }
}
