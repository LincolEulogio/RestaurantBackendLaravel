<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('es_ES');

        // Obtener todos los IDs de categorías
        $categoryIds = Category::pluck('id')->toArray();

        if (empty($categoryIds)) {
            $this->command->error('No hay categorías creadas. Ejecuta CategorySeeder primero.');

            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $name = $this->generateProductName($faker);

            Product::create([
                'name' => $name,
                'description' => $faker->sentence(10),
                'price' => $faker->randomFloat(2, 10, 80), // Precio entre 10 y 80
                'category_id' => $faker->randomElement($categoryIds),
                'is_available' => $faker->boolean(90), // 90% de probabilidad de estar disponible
                'image' => null, // Dejar null por ahora
            ]);
        }

        $this->command->info('50 Productos creados exitosamente.');
    }

    private function generateProductName($faker)
    {
        $adjectives = ['Especial', 'De la Casa', 'Supremo', 'Clásico', 'Picante', 'Dulce', 'Ahumado', 'Fresco', 'Criollo', 'Marinado'];
        $nouns = ['Pollo', 'Lomo', 'Pescado', 'Arroz', 'Tallarines', 'Ceviche', 'Causa', 'Ají de Gallina', 'Tacutacu', 'Seco'];

        return $faker->randomElement($nouns).' '.$faker->randomElement($adjectives);
    }
}
