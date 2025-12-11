<?php

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('waiter can update table status', function () {
    $waiter = User::factory()->create(['role' => 'waiter']);
    $table = Table::create([
        'table_number' => 'T100',
        'capacity' => 4,
        'status' => 'available',
        'location' => 'Indoor'
    ]);

    Sanctum::actingAs($waiter);

    $response = $this->patchJson("/api/waiter/tables/{$table->id}/status", [
        'status' => 'occupied'
    ]);

    $response->assertSuccessful();
    $this->assertEquals('occupied', $table->fresh()->status);

    $response = $this->patchJson("/api/waiter/tables/{$table->id}/status", [
        'status' => 'maintenance'
    ]);

    $response->assertSuccessful();
    $this->assertEquals('maintenance', $table->fresh()->status);

    $response = $this->patchJson("/api/waiter/tables/{$table->id}/status", [
        'status' => 'available'
    ]);

    $response->assertSuccessful();
    $this->assertEquals('available', $table->fresh()->status);
});

test('waiter cannot update table status with invalid data', function () {
    $waiter = User::factory()->create(['role' => 'waiter']);
    $table = Table::create([
        'table_number' => 'T101',
        'capacity' => 4,
        'status' => 'available',
        'location' => 'Indoor'
    ]);

    Sanctum::actingAs($waiter);

    $response = $this->patchJson("/api/waiter/tables/{$table->id}/status", [
        'status' => 'invalid_status'
    ]);

    $response->assertUnprocessable();
});

test('unauthenticated user cannot update table status', function () {
    $table = Table::create([
        'table_number' => 'T102',
        'capacity' => 4,
        'status' => 'available',
        'location' => 'Indoor'
    ]);

    $response = $this->patchJson("/api/waiter/tables/{$table->id}/status", [
        'status' => 'occupied'
    ]);

    $response->assertUnauthorized();
});
