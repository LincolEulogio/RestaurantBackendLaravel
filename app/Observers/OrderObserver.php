<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // If status changed to 'preparing' or 'confirmed' (depending on business logic)
        // let's do it when it moves to 'preparing' or 'ready'
        if ($order->isDirty('status') && in_array($order->status, ['preparing', 'ready'])) {
            $prevStatus = $order->getOriginal('status');
            
            // Only deduct once (e.g., from pending/confirmed to preparing)
            if (!in_array($prevStatus, ['preparing', 'ready', 'delivered'])) {
                try {
                    $this->deductInventory($order);
                } catch (\Throwable $e) {
                    Log::error("Failed to deduct inventory for Order #{$order->id}: " . $e->getMessage());
                }
            }
        }
    }

    protected function deductInventory(Order $order): void
    {
        $order->load('items.product.ingredients');

        foreach ($order->items as $item) {
            $product = $item->product;
            if (!$product) continue;

            foreach ($product->ingredients as $ingredient) {
                $quantityToDeduct = $ingredient->pivot->quantity * $item->quantity;
                
                $ingredient->decrement('stock_current', $quantityToDeduct);
                
                Log::info("Stock deducted: Product {$product->name}, Ingredient {$ingredient->name}, Amount {$quantityToDeduct}");
                
                // Low stock alert logic could go here
                if ($ingredient->stock_current <= $ingredient->stock_min) {
                    // event(new LowStockAlert($ingredient));
                }
            }
        }
    }
}
