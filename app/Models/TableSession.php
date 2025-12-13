<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableSession extends Model
{
    protected $fillable = [
        'table_id',
        'session_token',
        'status', // active, closed, paid
        'started_at',
        'ended_at',
        'total_amount',
        'waiter_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'session_token', 'session_token');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }
}
