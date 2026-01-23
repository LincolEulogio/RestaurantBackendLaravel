<?php

use App\Models\User;
use App\Models\InventoryItem;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'admin']);
});

test('inventory page is accessible', function () {
    actingAs($this->user)
        ->get(route('inventory.index'))
        ->assertOk();
});

test('can list inventory items via api', function () {
    InventoryItem::factory()->count(3)->create();

    actingAs($this->user)
        ->get(route('inventory-items.index'), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonCount(3);
});

test('can create inventory item', function () {
    $data = [
        'name' => 'Test Item',
        'sku' => 'TEST-SKU-001',
        'category' => 'Insumos',
        'stock_current' => 100,
        'stock_min' => 10,
        'unit' => 'kg',
        'price_unit' => 5.50,
    ];

    actingAs($this->user)
        ->post(route('inventory-items.store'), $data)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('inventory_items', [
        'sku' => 'TEST-SKU-001'
    ]);
});

test('can update inventory item', function () {
    $item = InventoryItem::factory()->create(['name' => 'Old Name']);

    actingAs($this->user)
        ->put(route('inventory-items.update', $item), [
            'name' => 'New Name',
            'sku' => $item->sku,
            'category' => $item->category,
            'stock_current' => 50,
            'stock_min' => 5,
            'unit' => $item->unit,
            'price_unit' => $item->price_unit,
        ])
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('inventory_items', [
        'id' => $item->id,
        'name' => 'New Name'
    ]);
});

test('can delete inventory item', function () {
    $item = InventoryItem::factory()->create();

    actingAs($this->user)
        ->delete(route('inventory-items.destroy', $item))
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('inventory_items', ['id' => $item->id]);
});
