<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES'); // Use Spanish locale for realistic data

        // Topics related to restaurant/food
        $topics = [
            'Secretos de la cocina peruana',
            'Cómo preparar el mejor ceviche',
            'La historia del Lomo Saltado',
            'Nuestros nuevos postres artesanales',
            'Eventos especiales para este mes',
            'Entrevista con nuestro Chef Principal',
            'Ingredientes frescos: Nuestra promesa',
            'Maridaje de vinos y carnes',
            'La importancia de la presentación en el plato',
            'Recetas rápidas para hacer en casa',
            'Beneficios de la comida mediterránea',
            'Nuestra huerta orgánica',
            'Celebrando el día del Pisco Sour',
            'Tendencias gastronómicas 2025',
            'El arte del buen café',
            'Brunch dominical: Lo que no te puedes perder',
            'Conoce a nuestro equipo de cocina',
            'Promociones de Happy Hour',
            'La fusión de sabores andinos',
            'Detrás de escena: Un día en el restaurante'
        ];

        foreach ($topics as $index => $topic) {
            $isPublished = $faker->boolean(80); // 80% chance of being published

            Blog::create([
                'title' => $topic,
                'slug' => Str::slug($topic) . '-' . ($index + 1), // Ensure unique slug
                'content' => $this->generateContent($faker),
                'image' => null, // We leave image null or could use a placeholder URL if requested
                'status' => $isPublished ? 'published' : 'draft',
                'published_at' => $isPublished ? $faker->dateTimeBetween('-1 year', 'now') : null,
            ]);
        }
    }

    private function generateContent($faker)
    {
        $paragraphs = $faker->paragraphs(3);
        $content = '';
        foreach ($paragraphs as $paragraph) {
            $content .= "<p>{$paragraph}</p>";
        }
        return $content;
    }
}
