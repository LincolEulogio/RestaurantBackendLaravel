<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_dni',
        'customer_name',
        'customer_lastname',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'total_orders',
        'total_spent',
        'last_order_date',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'last_order_date' => 'datetime',
    ];

    /**
     * Get all orders for this customer
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_email', 'customer_email')
            ->orWhere('customer_phone', $this->customer_phone)
            ->orWhere('customer_dni', $this->customer_dni);
    }

    /**
     * Get full name accessor
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->customer_name . ' ' . $this->customer_lastname);
    }

    /**
     * Get average order value
     */
    public function getAverageOrderValueAttribute(): float
    {
        if ($this->total_orders == 0) {
            return 0;
        }
        return round($this->total_spent / $this->total_orders, 2);
    }

    /**
     * Scope for active customers (ordered in last 6 months)
     */
    public function scopeActive($query)
    {
        return $query->where('last_order_date', '>=', now()->subMonths(6));
    }

    /**
     * Scope for top spenders
     */
    public function scopeTopSpenders($query, $limit = 10)
    {
        return $query->orderBy('total_spent', 'desc')->limit($limit);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_lastname', 'like', "%{$search}%")
                ->orWhere('customer_email', 'like', "%{$search}%")
                ->orWhere('customer_phone', 'like', "%{$search}%")
                ->orWhere('customer_dni', 'like', "%{$search}%");
        });
    }

    /**
     * Update customer statistics from orders
     */
    public function updateStats(): void
    {
        $orders = Order::where(function ($query) {
            $query->where('customer_email', $this->customer_email)
                ->orWhere('customer_phone', $this->customer_phone)
                ->orWhere('customer_dni', $this->customer_dni);
        })->where('status', '!=', 'cancelled')->get();

        $this->total_orders = $orders->count();
        $this->total_spent = $orders->sum('total');
        $this->last_order_date = $orders->max('created_at');
        $this->save();
    }
}
