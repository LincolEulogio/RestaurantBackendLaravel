<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'image_url',
        'image_public_id',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(InventoryItem::class, 'product_inventory')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
