<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create([
        'role' => 'superadmin',
    ]);
});

test('dashboard page is accessible', function () {
    actingAs($this->user)
        ->get(route('dashboard'))
        ->assertOk();
});

test('inventory page is accessible', function () {
    actingAs($this->user)
        ->get(route('inventory.index'))
        ->assertOk();
});

test('reports page is accessible', function () {
    actingAs($this->user)
        ->get(route('reports.index'))
        ->assertOk();
});

test('kitchen page is accessible', function () {
    actingAs($this->user)
        ->get(route('kitchen.index'))
        ->assertOk();
});

test('billing page is accessible', function () {
    actingAs($this->user)
        ->get(route('billing.index'))
        ->assertOk();
});

test('reservations page is accessible', function () {
    actingAs($this->user)
        ->get(route('reservations.index'))
        ->assertOk();
});

test('inventory export routes are functional', function () {
    actingAs($this->user);
    
    get(route('inventory.export.excel'))->assertOk();
    get(route('inventory.export.pdf'))->assertOk();
    get(route('inventory.print'))->assertOk();
});

test('report export routes are functional', function () {
    actingAs($this->user);
    
    get(route('reports.export.excel'))->assertOk();
    get(route('reports.export.pdf'))->assertOk();
    get(route('reports.print'))->assertOk();
});
