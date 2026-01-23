<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'table_id' => Table::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'customer_phone' => $this->faker->phoneNumber(),
            'reservation_date' => now()->addDays($this->faker->numberBetween(1, 7))->format('Y-m-d'),
            'reservation_time' => $this->faker->time('H:i'),
            'party_size' => $this->faker->numberBetween(1, 10),
            'status' => 'pending',
            'special_request' => $this->faker->sentence(),
        ];
    }
}
