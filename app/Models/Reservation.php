<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'table_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'reservation_date',
        'reservation_time',
        'party_size',
        'status',
        'special_request'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
