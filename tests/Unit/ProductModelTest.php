<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Promotion;

it('has relationship with category', function () {
    $category = Category::factory()->create(['name' => 'Bebidas']);
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->category)->toBeInstanceOf(Category::class);
    expect($product->category->name)->toBe('Bebidas');
});

it('has relationship with order items', function () {
    $product = Product::factory()->create();
    OrderItem::factory()->count(3)->create(['product_id' => $product->id]);

    expect($product->orderItems)->toHaveCount(3);
});

it('can have multiple promotions', function () {
    $product = Product::factory()->create();
    $promotion1 = Promotion::factory()->create();
    $promotion2 = Promotion::factory()->create();

    $product->promotions()->attach([$promotion1->id, $promotion2->id]);

    expect($product->promotions)->toHaveCount(2);
});

it('casts price as decimal', function () {
    $product = Product::factory()->create(['price' => 25.50]);

    expect($product->price)->toBe('25.50');
});

it('stores image URL correctly', function () {
    $product = Product::factory()->create([
        'image_url' => 'https://example.com/image.jpg',
    ]);

    expect($product->image_url)->toBe('https://example.com/image.jpg');
});

it('can be marked as available or unavailable', function () {
    $product = Product::factory()->create(['available' => true]);
    expect($product->available)->toBeTrue();

    $product->available = false;
    $product->save();
    expect($product->available)->toBeFalse();
});

it('has soft deletes enabled', function () {
    $product = Product::factory()->create();
    $productId = $product->id;

    $product->delete();

    expect(Product::find($productId))->toBeNull();
    expect(Product::withTrashed()->find($productId))->not->toBeNull();
});

it('can be restored after soft delete', function () {
    $product = Product::factory()->create(['name' => 'Test Product']);
    $product->delete();

    $product->restore();

    expect(Product::find($product->id))->not->toBeNull();
    expect(Product::find($product->id)->name)->toBe('Test Product');
});

it('stores cloudinary public ID', function () {
    $product = Product::factory()->create([
        'cloudinary_public_id' => 'products/abc123',
    ]);

    expect($product->cloudinary_public_id)->toBe('products/abc123');
});

it('belongs to a category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'category_id' => $category->id,
    ]);
});
