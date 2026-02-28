<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AuditLog;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_type',
        'customer_name',
        'customer_document_type',
        'customer_document_number',
        'customer_address',
        'subtotal',
        'tax',
        'total',
        'status',
        'pdf_path',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
