<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'image', 'image_url', 'image_public_id', 'status', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
