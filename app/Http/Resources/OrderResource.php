<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer' => [
                'name' => $this->customer_name,
                'lastname' => $this->customer_lastname,
                'dni' => $this->customer_dni,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'type' => $this->order_type,
            'source' => $this->order_source,
            'status' => [
                'code' => $this->status,
                'label' => $this->status_label,
                'badge' => $this->status_badge,
            ],
            'totals' => [
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'delivery_fee' => $this->delivery_fee,
                'total' => $this->total,
            ],
            'items' => $this->whenLoaded('items', function() {
                return $this->items->map(fn($item) => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'notes' => $item->notes ?? $item->special_instructions,
                ]);
            }),
            'table' => $this->whenLoaded('table'),
            'waiter' => $this->whenLoaded('waiter'),
            'dates' => [
                'created' => $this->created_at,
                'confirmed' => $this->confirmed_at,
                'ready' => $this->ready_at,
                'delivered' => $this->delivered_at,
            ],
        ];
    }
}
