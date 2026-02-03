<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_date',
        'order_type',
        'order_source',
        'customer_name',
        'customer_lastname',
        'customer_email',
        'customer_phone',
        'customer_dni',
        'delivery_address',
        'delivery_district',
        'delivery_reference',
        'table_number',
        'subtotal',
        'tax',
        'delivery_fee',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'notes',
        'confirmed_at',
        'ready_at',
        'in_transit_at',
        'delivered_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'ready_at' => 'datetime',
        'in_transit_at' => 'datetime',
        'delivered_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the status history for the order.
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the table for the order.
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }


    /**
     * Update the order status and log the change.
     */
    public function updateStatus(string $newStatus, ?int $userId = null, ?string $notes = null): void
    {
        $oldStatus = $this->status;

        // Update the status
        $this->status = $newStatus;

        // Update timestamps based on status
        if ($newStatus === 'confirmed' && ! $this->confirmed_at) {
            $this->confirmed_at = now();
        } elseif ($newStatus === 'ready' && ! $this->ready_at) {
            $this->ready_at = now();
        } elseif ($newStatus === 'in_transit' && ! $this->in_transit_at) {
            $this->in_transit_at = now();
        } elseif ($newStatus === 'delivered' && ! $this->delivered_at) {
            $this->delivered_at = now();
        }

        $this->save();

        // Log the status change
        $this->statusHistory()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'user_id' => $userId,
            'notes' => $notes,
        ]);
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));

        return "ORD-{$date}-{$random}";
    }

    /**
     * Scope a query to only include orders of a given status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include orders of a given type.
     */
    public function scopeType($query, $type)
    {
        return $query->where('order_type', $type);
    }

    /**
     * Scope a query to only include orders from a given source.
     */
    public function scopeSource($query, $source)
    {
        return $query->where('order_source', $source);
    }

    /**
     * Get the order's full customer name.
     */
    public function getFullCustomerNameAttribute(): string
    {
        return trim($this->customer_name . ' ' . $this->customer_lastname);
    }

    /**
     * Check if the order is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the order is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if the order is preparing.
     */
    public function isPreparing(): bool
    {
        return $this->status === 'preparing';
    }

    /**
     * Check if the order is ready.
     */
    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    /**
     * Check if the order is in transit.
     */
    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    /**
     * Check if the order is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if the order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'preparing' => 'En Preparación',
            'ready' => 'Listo',
            'in_transit' => 'En Camino',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            default => 'Desconocido',
        };
    }
}
