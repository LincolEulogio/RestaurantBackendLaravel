<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'admin']);
});

test('reports calculation logic is accurate', function () {
    // Zero out old orders
    Order::query()->delete();
    
    // Create an order with items
    $order = Order::factory()->create([
        'status' => 'delivered',
        'delivered_at' => now(),
        'total' => 100.00
    ]);

    $product = Product::factory()->create(['name' => 'Special Dish']);
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 2,
        'unit_price' => 50.00,
        'subtotal' => 100.00
    ]);

    actingAs($this->user)
        ->get(route('reports.index'))
        ->assertOk()
        ->assertViewHas('totalRevenue', 100.00)
        ->assertViewHas('completedOrders', 1);
});

test('reports filter by date correctly', function () {
    Order::query()->delete();

    // Past order (out of default 30 days window)
    Order::factory()->create([
        'status' => 'delivered',
        'delivered_at' => now()->subDays(40),
        'total' => 500.00
    ]);

    // Current order
    Order::factory()->create([
        'status' => 'delivered',
        'delivered_at' => now(),
        'total' => 100.00
    ]);

    actingAs($this->user)
        ->get(route('reports.index'))
        ->assertOk()
        ->assertViewHas('totalRevenue', 100.00); // Should only count the current one
});
