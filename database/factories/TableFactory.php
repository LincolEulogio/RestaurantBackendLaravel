<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'table_number' => 'T-' . $this->faker->unique()->numberBetween(1, 100),
            'capacity' => $this->faker->randomElement([2, 4, 6, 8]),
            'status' => 'available',
            'location' => $this->faker->word(),
        ];
    }
}
