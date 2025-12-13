<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar tabla antes de sembrar
        // DB::table('categories')->truncate(); // Opcional, cuidado en producción

        $categories = [
            'Entradas',
            'Platos de Fondo',
            'Sopas',
            'Ensaladas',
            'Postres',
            'Bebidas Calientes',
            'Bebidas Frías',
            'Cócteles',
            'Vinos',
            'Cervezas',
            'Sandwiches',
            'Hamburguesas',
            'Pizzas',
            'Pastas',
            'Parrillas',
            'Mariscos',
            'Vegetariano',
            'Vegano',
            'Desayunos',
            'Menú Infantil',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => 'Deliciosa selección de '.strtolower($categoryName).' preparados con los mejores ingredientes.',
                'is_active' => true,
            ]);
        }

        $this->command->info('20 Categorías creadas exitosamente.');
    }
}
