<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'order_date',
        'customer_name',
        'customer_lastname',
        'customer_dni',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'order_type',
        'payment_method',
        'status',
        'subtotal',
        'tax',
        'delivery_fee',
        'total',
        'notes',
        'confirmed_at',
        'ready_at',
        'delivered_at',
        'table_id',
        'waiter_id',
        'order_source', // online, waiter, qr_self_service
        'session_token',
        'payment_status',
        'payment_method',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'order_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }

            // Set order_date to current timestamp if not already set
            if (empty($order->order_date)) {
                $order->order_date = now();
            }
        });
    }

    // Relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function scopePresencial($query)
    {
        return $query->whereIn('order_source', ['waiter', 'qr_self_service']);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Methods
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));

        return "{$prefix}-{$date}-{$random}";
    }

    public function updateStatus($newStatus, $userId = null, $notes = null)
    {
        $oldStatus = $this->status;

        $this->status = $newStatus;

        // Update timestamps based on status
        if ($newStatus === 'confirmed' && ! $this->confirmed_at) {
            $this->confirmed_at = now();
        } elseif ($newStatus === 'ready' && ! $this->ready_at) {
            $this->ready_at = now();
        } elseif ($newStatus === 'delivered' && ! $this->delivered_at) {
            $this->delivered_at = now();
        }

        $this->save();

        // Record status change in history
        $this->statusHistory()->create([
            'user_id' => $userId,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $notes,
        ]);

        // Emit WebSocket Event
        event(new \App\Events\OrderStatusChanged($this));

        return $this;
    }

    public function calculateTotal()
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal + $this->tax + $this->delivery_fee;
        $this->save();

        return $this;
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'delivered' => 'secondary',
            'cancelled' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'preparing' => 'Preparando',
            'ready' => 'Listo',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
