<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => Order::generateOrderNumber(),
            'order_date' => now(),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'status' => 'pending',
            'order_source' => 'web',
            'order_type' => 'delivery',
            'payment_status' => 'pending',
            'total' => fake()->randomFloat(2, 20, 100),
            'subtotal' => fake()->randomFloat(2, 10, 80),
            'tax' => 0,
            'delivery_fee' => 0,
        ];
    }
}
