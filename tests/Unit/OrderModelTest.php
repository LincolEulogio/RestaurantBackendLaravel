<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;

it('generates unique order numbers', function () {
    $order1 = Order::factory()->create();
    $order2 = Order::factory()->create();

    expect($order1->order_number)->not->toBe($order2->order_number);
    expect($order1->order_number)->toStartWith('ORD-');
});

it('updates status and records history', function () {
    $order = Order::factory()->create(['status' => 'pending']);
    $user = User::factory()->create();

    $order->updateStatus('confirmed', $user->id, 'Confirmado por admin');

    expect($order->status)->toBe('confirmed');
    expect($order->confirmed_at)->not->toBeNull();
    
    $this->assertDatabaseHas('order_status_history', [
        'order_id' => $order->id,
        'from_status' => 'pending',
        'to_status' => 'confirmed',
        'user_id' => $user->id,
    ]);
});

it('calculates total from items', function () {
    $order = Order::factory()->create([
        'subtotal' => 0,
        'tax' => 5.00,
        'delivery_fee' => 10.00,
    ]);

    $product1 = Product::factory()->create(['price' => 25.00]);
    $product2 = Product::factory()->create(['price' => 15.00]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product1->id,
        'quantity' => 2,
        'unit_price' => 25.00,
        'subtotal' => 50.00,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product2->id,
        'quantity' => 1,
        'unit_price' => 15.00,
        'subtotal' => 15.00,
    ]);

    $order->calculateTotal();

    expect($order->subtotal)->toBe('65.00');
    expect($order->total)->toBe('80.00'); // 65 + 5 + 10
});

it('has relationship with items', function () {
    $order = Order::factory()->create();
    $product = Product::factory()->create();
    
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
    ]);

    expect($order->items)->toHaveCount(1);
    expect($order->items->first())->toBeInstanceOf(OrderItem::class);
});

it('has relationship with table', function () {
    $table = Table::factory()->create();
    $order = Order::factory()->create(['table_id' => $table->id]);

    expect($order->table)->toBeInstanceOf(Table::class);
    expect($order->table->id)->toBe($table->id);
});

it('has relationship with waiter', function () {
    $waiter = User::factory()->create();
    $order = Order::factory()->create(['waiter_id' => $waiter->id]);

    expect($order->waiter)->toBeInstanceOf(User::class);
    expect($order->waiter->id)->toBe($waiter->id);
});

it('scopes presencial orders correctly', function () {
    Order::factory()->create(['order_source' => 'waiter']);
    Order::factory()->create(['order_source' => 'qr_self_service']);
    Order::factory()->create(['order_source' => 'web']);

    $presencialOrders = Order::presencial()->get();

    expect($presencialOrders)->toHaveCount(2);
});

it('scopes orders by status', function () {
    Order::factory()->count(2)->create(['status' => 'pending']);
    Order::factory()->count(3)->create(['status' => 'confirmed']);
    Order::factory()->create(['status' => 'ready']);

    expect(Order::pending()->get())->toHaveCount(2);
    expect(Order::confirmed()->get())->toHaveCount(3);
    expect(Order::ready()->get())->toHaveCount(1);
});

it('returns correct status badge', function () {
    $order = Order::factory()->create(['status' => 'pending']);
    expect($order->status_badge)->toBe('warning');

    $order->status = 'confirmed';
    expect($order->status_badge)->toBe('info');

    $order->status = 'ready';
    expect($order->status_badge)->toBe('success');
});

it('returns correct status label in Spanish', function () {
    $order = Order::factory()->create(['status' => 'pending']);
    expect($order->status_label)->toBe('Pendiente');

    $order->status = 'preparing';
    expect($order->status_label)->toBe('Preparando');

    $order->status = 'delivered';
    expect($order->status_label)->toBe('Entregado');
});

it('sets order date automatically on creation', function () {
    $order = Order::factory()->create(['order_date' => null]);

    expect($order->order_date)->not->toBeNull();
});

it('updates timestamps when status changes to ready', function () {
    $order = Order::factory()->create(['status' => 'preparing', 'ready_at' => null]);

    $order->updateStatus('ready', null);

    expect($order->ready_at)->not->toBeNull();
});

it('updates timestamps when status changes to delivered', function () {
    $order = Order::factory()->create(['status' => 'ready', 'delivered_at' => null]);

    $order->updateStatus('delivered', null);

    expect($order->delivered_at)->not->toBeNull();
});
