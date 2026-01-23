<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Table;
use App\Models\Reservation;
use Database\Seeders\RoleSeeder;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(RoleSeeder::class);
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->kitchenUser = User::factory()->create(['role' => 'cocinero']);
    $this->billingUser = User::factory()->create(['role' => 'cajero']);
});

test('kitchen can status an order as preparing', function () {
    $order = Order::factory()->create(['status' => 'confirmed']);

    actingAs($this->kitchenUser)
        ->patch(route('kitchen.update-status', $order), ['status' => 'preparing'])
        ->assertRedirect(route('kitchen.index'));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'preparing'
    ]);
});

test('billing can process payment and complete order', function () {
    $order = Order::factory()->create([
        'status' => 'ready',
        'total' => 50.00
    ]);

    actingAs($this->billingUser)
        ->post(route('billing.process-payment', $order), [
            'payment_method' => 'cash',
            'amount_received' => 50.00
        ])
        ->assertRedirect(route('billing.index'));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'delivered',
        'payment_status' => 'paid'
    ]);
});

test('can list reservations', function () {
    Reservation::factory()->count(3)->create();

    actingAs($this->admin)
        ->get(route('reservations.index'))
        ->assertOk();
});

test('can create reservation', function () {
    $table = Table::factory()->create(['status' => 'available']);

    $data = [
        'customer_name' => 'John Doe',
        'customer_email' => 'john@example.com',
        'customer_phone' => '987654321',
        'reservation_date' => now()->addDay()->format('Y-m-d'),
        'reservation_time' => '19:00',
        'party_size' => 2,
        'table_id' => $table->id,
    ];

    actingAs($this->admin)
        ->post(route('reservations.store'), $data)
        ->assertRedirect(route('reservations.index'));

    $this->assertDatabaseHas('reservations', [
        'customer_name' => 'John Doe',
        'table_id' => $table->id
    ]);
});
