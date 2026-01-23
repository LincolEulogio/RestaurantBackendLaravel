<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'category' => $this->faker->randomElement(['Insumos', 'Limpieza', 'Bebidas']),
            'stock_current' => $this->faker->randomFloat(2, 10, 100),
            'stock_min' => $this->faker->randomFloat(2, 5, 15),
            'unit' => $this->faker->randomElement(['kg', 'lt', 'und']),
            'price_unit' => $this->faker->randomFloat(2, 1, 50),
            'is_active' => true,
        ];
    }
}
