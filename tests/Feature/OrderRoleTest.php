<?php

use App\Models\Order;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    // Ensure roles exist with permissions
    Role::firstOrCreate(['slug' => 'cashier'], [
        'name' => 'Cashier',
        'permissions' => ['orders' => true],
    ]);
    Role::firstOrCreate(['slug' => 'delivery'], [
        'name' => 'Delivery',
        'permissions' => ['orders' => true],
    ]);
    Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'permissions' => []]);
});

test('cashier only sees non-web orders and correct stats', function () {
    $cashier = User::factory()->create(['role' => 'cashier']);

    // Create mixed orders
    $waiterOrder = Order::factory()->create(['order_source' => 'waiter', 'status' => 'pending']);
    $webOrder = Order::factory()->create(['order_source' => 'web', 'status' => 'pending']);

    $response = $this->actingAs($cashier)->get(route('orders.index'));

    $response->assertStatus(200);

    // Check List Visibility
    $response->assertSee($waiterOrder->order_number);
    $response->assertDontSee($webOrder->order_number);

    // Check Stats (View Data)
    $response->assertViewHas('totalOrders', 1); // Only the waiter order
    $response->assertViewHas('pendingOrders', 1);
});

test('delivery only sees web orders and correct stats', function () {
    $delivery = User::factory()->create(['role' => 'delivery']);

    // Create mixed orders
    $waiterOrder = Order::factory()->create(['order_source' => 'waiter', 'status' => 'pending']);
    $webOrder = Order::factory()->create(['order_source' => 'web', 'status' => 'pending']);
    $onlineOrder = Order::factory()->create(['order_source' => 'online', 'status' => 'confirmed']);

    $response = $this->actingAs($delivery)->get(route('orders.index'));

    $response->assertStatus(200);

    // Check List Visibility
    $response->assertDontSee($waiterOrder->order_number);
    $response->assertSee($webOrder->order_number);
    $response->assertSee($onlineOrder->order_number);

    // Check Stats
    $response->assertViewHas('totalOrders', 2); // web + online
    $response->assertViewHas('pendingOrders', 1); // web only
    $response->assertViewHas('inProgressOrders', 1); // online (confirmed)
});

test('admin sees all orders', function () {
    // Admin usually has 'admin' user role or similar logic.
    // Assuming 'admin' role sees all.
    $admin = User::factory()->create(['role' => 'admin']);

    $waiterOrder = Order::factory()->create(['order_source' => 'waiter']);
    $webOrder = Order::factory()->create(['order_source' => 'web']);

    $response = $this->actingAs($admin)->get(route('orders.index'));

    $response->assertStatus(200);
    $response->assertSee($waiterOrder->order_number);
    $response->assertSee($webOrder->order_number);
    $response->assertViewHas('totalOrders', 2);
});
