<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $this->syncCustomer($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Sync customer data if customer info changed
        if ($order->isDirty(['customer_name', 'customer_email', 'customer_phone', 'customer_dni'])) {
            $this->syncCustomer($order);
        }

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

    /**
     * Sync customer from order data
     */
    protected function syncCustomer(Order $order): void
    {
        // Skip if no customer info
        if (!$order->customer_email && !$order->customer_phone && !$order->customer_dni) {
            return;
        }

        try {
            // Find or create customer
            $customer = Customer::where(function ($query) use ($order) {
                if ($order->customer_email) {
                    $query->where('customer_email', $order->customer_email);
                }
                if ($order->customer_phone) {
                    $query->orWhere('customer_phone', $order->customer_phone);
                }
                if ($order->customer_dni) {
                    $query->orWhere('customer_dni', $order->customer_dni);
                }
            })->first();

            if ($customer) {
                // Update customer info if changed
                $customer->update([
                    'customer_name' => $order->customer_name ?? $customer->customer_name,
                    'customer_lastname' => $order->customer_lastname ?? $customer->customer_lastname,
                    'customer_email' => $order->customer_email ?? $customer->customer_email,
                    'customer_phone' => $order->customer_phone ?? $customer->customer_phone,
                    'customer_dni' => $order->customer_dni ?? $customer->customer_dni,
                    'delivery_address' => $order->delivery_address ?? $customer->delivery_address,
                ]);
            } else {
                // Create new customer
                $customer = Customer::create([
                    'customer_name' => $order->customer_name,
                    'customer_lastname' => $order->customer_lastname,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'customer_dni' => $order->customer_dni,
                    'delivery_address' => $order->delivery_address,
                ]);
            }

            // Update customer statistics
            $customer->updateStats();

            Log::info("Customer synced from Order #{$order->id}");
        } catch (\Throwable $e) {
            Log::error("Failed to sync customer from Order #{$order->id}: " . $e->getMessage());
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
