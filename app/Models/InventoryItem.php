<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sku',
        'category',
        'stock_current',
        'stock_min',
        'unit',
        'price_unit',
        'is_active',
    ];

    protected $casts = [
        'stock_current' => 'decimal:2',
        'stock_min' => 'decimal:2',
        'price_unit' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
