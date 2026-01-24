<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;

beforeEach(function () {
    $this->product = Product::factory()->create([
        'price' => 25.00,
        'available' => true,
    ]);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('creates an order via API without authentication', function () {
    $orderData = [
        'customer_name' => 'Juan',
        'customer_lastname' => 'Pérez',
        'customer_email' => 'juan@example.com',
        'customer_phone' => '987654321',
        'delivery_address' => 'Av. Principal 123',
        'order_type' => 'delivery',
        'payment_method' => 'cash',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 2,
                'notes' => 'Sin cebolla',
            ],
        ],
    ];

    $response = postJson('/api/orders', $orderData);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'order' => [
                'id',
                'order_number',
                'customer_name',
                'total',
                'status',
            ],
        ]);

    $this->assertDatabaseHas('orders', [
        'customer_email' => 'juan@example.com',
        'order_type' => 'delivery',
        'status' => 'pending',
    ]);

    $this->assertDatabaseHas('order_items', [
        'product_id' => $this->product->id,
        'quantity' => 2,
        'notes' => 'Sin cebolla',
    ]);
});

it('calculates order total correctly', function () {
    $orderData = [
        'customer_name' => 'María',
        'customer_lastname' => 'García',
        'customer_email' => 'maria@example.com',
        'customer_phone' => '987654321',
        'order_type' => 'pickup',
        'payment_method' => 'card',
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 3,
            ],
        ],
    ];

    $response = postJson('/api/orders', $orderData);

    $response->assertSuccessful();
    
    $order = Order::where('customer_email', 'maria@example.com')->first();
    expect($order->subtotal)->toBe('75.00'); // 25 * 3
});

it('requires authentication to list orders', function () {
    $response = getJson('/api/orders');

    $response->assertUnauthorized();
});

it('lists orders for authenticated users', function () {
    Order::factory()->count(3)->create();

    $response = actingAs($this->admin, 'sanctum')
        ->getJson('/api/orders');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'order_number',
                    'customer_name',
                    'status',
                    'total',
                ],
            ],
        ]);
});

it('updates order status with authentication', function () {
    $order = Order::factory()->create(['status' => 'pending']);

    $response = actingAs($this->admin, 'sanctum')
        ->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'confirmed',
        ]);

    $response->assertSuccessful();

    $order->refresh();
    expect($order->status)->toBe('confirmed');
    expect($order->confirmed_at)->not->toBeNull();
});

it('validates required fields when creating order', function () {
    $response = postJson('/api/orders', [
        'customer_name' => 'Test',
        // Missing required fields
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['customer_email', 'customer_phone', 'items']);
});

it('validates items array is not empty', function () {
    $response = postJson('/api/orders', [
        'customer_name' => 'Test',
        'customer_lastname' => 'User',
        'customer_email' => 'test@example.com',
        'customer_phone' => '987654321',
        'order_type' => 'delivery',
        'payment_method' => 'cash',
        'items' => [], // Empty items
    ]);

    $response->assertUnprocessable();
});

it('prevents ordering unavailable products', function () {
    $unavailableProduct = Product::factory()->create(['available' => false]);

    $response = postJson('/api/orders', [
        'customer_name' => 'Test',
        'customer_lastname' => 'User',
        'customer_email' => 'test@example.com',
        'customer_phone' => '987654321',
        'order_type' => 'delivery',
        'payment_method' => 'cash',
        'items' => [
            [
                'product_id' => $unavailableProduct->id,
                'quantity' => 1,
            ],
        ],
    ]);

    $response->assertUnprocessable();
});
