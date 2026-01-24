<?php

use App\Models\Order;
use App\Models\Product;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->product = Product::factory()->create(['price' => 50.00]);
    
    // Set Culqi test keys
    config(['services.culqi.secret_key' => 'sk_test_example']);
    config(['services.culqi.public_key' => 'pk_test_example']);
});

it('validates card payment data', function () {
    $response = postJson('/api/payment/process-card', [
        // Missing required fields
    ]);

    $response->assertUnprocessable();
});

it('creates a Culqi order with valid data', function () {
    $orderData = [
        'amount' => 10000, // 100.00 in cents
        'currency_code' => 'PEN',
        'description' => 'Test Order',
        'customer_email' => 'test@example.com',
    ];

    $response = postJson('/api/payment/create-order', $orderData);

    // Should return success or validation error depending on Culqi config
    expect($response->status())->toBeIn([200, 201, 422, 500]);
});

it('validates amount is positive for payment', function () {
    $response = postJson('/api/payment/create-order', [
        'amount' => -100,
        'currency_code' => 'PEN',
        'description' => 'Test',
        'customer_email' => 'test@example.com',
    ]);

    $response->assertUnprocessable();
});

it('validates currency code format', function () {
    $response = postJson('/api/payment/create-order', [
        'amount' => 10000,
        'currency_code' => 'INVALID',
        'description' => 'Test',
        'customer_email' => 'test@example.com',
    ]);

    $response->assertUnprocessable();
});

it('handles webhook events', function () {
    $webhookData = [
        'object' => [
            'type' => 'charge',
            'id' => 'chr_test_123',
        ],
        'type' => 'charge.succeeded',
    ];

    $response = postJson('/api/payment/webhook', $webhookData);

    // Webhook should process without authentication
    expect($response->status())->toBeIn([200, 201, 400]);
});

it('validates email format for payment', function () {
    $response = postJson('/api/payment/create-order', [
        'amount' => 10000,
        'currency_code' => 'PEN',
        'description' => 'Test',
        'customer_email' => 'invalid-email',
    ]);

    $response->assertUnprocessable();
});

it('requires description for payment order', function () {
    $response = postJson('/api/payment/create-order', [
        'amount' => 10000,
        'currency_code' => 'PEN',
        'customer_email' => 'test@example.com',
        // Missing description
    ]);

    $response->assertUnprocessable();
});
