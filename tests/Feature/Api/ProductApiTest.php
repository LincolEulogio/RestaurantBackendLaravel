<?php

use App\Models\Category;
use App\Models\Product;
use function Pest\Laravel\getJson;

beforeEach(function () {
    // Create test categories
    $this->category = Category::factory()->create(['name' => 'Bebidas']);
    $this->category2 = Category::factory()->create(['name' => 'Comidas']);
});

it('returns all products via API', function () {
    Product::factory()->count(5)->create(['category_id' => $this->category->id]);

    $response = getJson('/api/products');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'category',
                    'image',
                    'available',
                ],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(5);
});

it('filters products by category', function () {
    Product::factory()->count(3)->create(['category_id' => $this->category->id]);
    Product::factory()->count(2)->create(['category_id' => $this->category2->id]);

    $response = getJson("/api/products?category_id={$this->category->id}");

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(3);
});

it('returns only available products when filtered', function () {
    Product::factory()->count(3)->create(['category_id' => $this->category->id, 'available' => true]);
    Product::factory()->count(2)->create(['category_id' => $this->category->id, 'available' => false]);

    $response = getJson('/api/products?available=1');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(3);
});

it('includes category information in product response', function () {
    $product = Product::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Coca Cola',
    ]);

    $response = getJson('/api/products');

    $response->assertSuccessful()
        ->assertJsonFragment([
            'name' => 'Coca Cola',
            'category' => [
                'id' => $this->category->id,
                'name' => 'Bebidas',
            ],
        ]);
});

it('returns empty array when no products exist', function () {
    $response = getJson('/api/products');

    $response->assertSuccessful()
        ->assertJson(['data' => []]);
});
