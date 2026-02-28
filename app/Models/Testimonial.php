<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'role', 
        'image_url',
        'image_public_id',
        'rating', 
        'text', 
        'platform', 
        'date_literal', 
        'is_verified', 
        'is_active'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];
}
