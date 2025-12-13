<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'table_number',
        'capacity',
        'status', // available, occupied, reserved, cleaning
        'location',
        'qr_code',
        'current_session_id',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function currentSession()
    {
        return $this->belongsTo(TableSession::class, 'current_session_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
