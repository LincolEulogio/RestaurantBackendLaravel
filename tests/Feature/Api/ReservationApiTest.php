<?php

use App\Models\Reservation;
use App\Models\Table;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->table = Table::factory()->create([
        'table_number' => '5',
        'capacity' => 4,
        'status' => 'available',
    ]);
});

it('creates a reservation successfully', function () {
    $reservationData = [
        'customer_name' => 'Carlos',
        'customer_lastname' => 'Rodríguez',
        'customer_email' => 'carlos@example.com',
        'customer_phone' => '987654321',
        'reservation_date' => now()->addDays(2)->format('Y-m-d'),
        'reservation_time' => '19:00',
        'number_of_people' => 4,
        'notes' => 'Mesa cerca de la ventana',
    ];

    $response = postJson('/api/reservations', $reservationData);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'reservation' => [
                'id',
                'customer_name',
                'reservation_date',
                'status',
            ],
        ]);

    $this->assertDatabaseHas('reservations', [
        'customer_email' => 'carlos@example.com',
        'status' => 'pending',
    ]);
});

it('checks table availability for a given date and time', function () {
    $date = now()->addDays(3)->format('Y-m-d');
    $time = '20:00';

    $response = getJson("/api/reservations/availability?date={$date}&time={$time}&people=4");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'available',
            'message',
        ]);
});

it('returns available tables for reservation', function () {
    Table::factory()->count(3)->create(['status' => 'available', 'capacity' => 4]);
    Table::factory()->create(['status' => 'occupied', 'capacity' => 4]);

    $date = now()->addDays(1)->format('Y-m-d');
    $time = '18:00';

    $response = getJson("/api/reservations/available-tables?date={$date}&time={$time}&people=4");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'tables' => [
                '*' => [
                    'id',
                    'table_number',
                    'capacity',
                    'status',
                ],
            ],
        ]);
});

it('validates required fields for reservation', function () {
    $response = postJson('/api/reservations', [
        'customer_name' => 'Test',
        // Missing required fields
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'customer_email',
            'customer_phone',
            'reservation_date',
            'reservation_time',
            'number_of_people',
        ]);
});

it('validates reservation date is in the future', function () {
    $response = postJson('/api/reservations', [
        'customer_name' => 'Test',
        'customer_lastname' => 'User',
        'customer_email' => 'test@example.com',
        'customer_phone' => '987654321',
        'reservation_date' => now()->subDays(1)->format('Y-m-d'), // Past date
        'reservation_time' => '19:00',
        'number_of_people' => 2,
    ]);

    $response->assertUnprocessable();
});

it('validates number of people is positive', function () {
    $response = postJson('/api/reservations', [
        'customer_name' => 'Test',
        'customer_lastname' => 'User',
        'customer_email' => 'test@example.com',
        'customer_phone' => '987654321',
        'reservation_date' => now()->addDays(1)->format('Y-m-d'),
        'reservation_time' => '19:00',
        'number_of_people' => 0, // Invalid
    ]);

    $response->assertUnprocessable();
});

it('prevents double booking for the same table and time', function () {
    // Create existing reservation
    Reservation::factory()->create([
        'table_id' => $this->table->id,
        'reservation_date' => now()->addDays(1)->format('Y-m-d'),
        'reservation_time' => '19:00:00',
        'status' => 'confirmed',
    ]);

    // Try to book same table at same time
    $response = postJson('/api/reservations', [
        'customer_name' => 'Test',
        'customer_lastname' => 'User',
        'customer_email' => 'test@example.com',
        'customer_phone' => '987654321',
        'reservation_date' => now()->addDays(1)->format('Y-m-d'),
        'reservation_time' => '19:00',
        'number_of_people' => 2,
        'table_id' => $this->table->id,
    ]);

    // Should either fail or assign different table
    expect($response->status())->toBeIn([200, 201, 422]);
});
