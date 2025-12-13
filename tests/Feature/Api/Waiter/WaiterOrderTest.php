<?php

use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('waiter can create order and session', function () {
    $waiter = User::factory()->create(['role' => 'waiter']);
    $table = Table::create([
        'table_number' => 'T200',
        'capacity' => 4,
        'status' => 'available',
    ]);
    $product = Product::factory()->create(['price' => 20]);

    Sanctum::actingAs($waiter);

    $response = $this->postJson('/api/waiter/orders', [
        'table_id' => $table->id,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2],
        ],
    ]);

    $response->dump();
    $response->assertCreated();

    // Verify session created
    $this->assertDatabaseHas('table_sessions', [
        'table_id' => $table->id,
        'status' => 'active',
        'waiter_id' => $waiter->id,
    ]);

    // Verify order created
    $this->assertDatabaseHas('orders', [
        'table_id' => $table->id,
        'waiter_id' => $waiter->id,
        'total' => 40,
    ]);

    // Verify table status updated
    $this->assertEquals('occupied', $table->fresh()->status);
});
