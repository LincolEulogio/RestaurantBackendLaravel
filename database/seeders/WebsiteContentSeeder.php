<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\RestaurantValue;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $testimonials = [
            [
                'name' => 'Lucía Ramírez',
                'role' => 'Food Blogger',
                'rating' => 5,
                'text' => 'La experiencia fue increíble, sabores auténticos y excelente atención.',
                'platform' => 'Google Reviews',
                'date_literal' => 'Hace 2 semanas',
                'is_verified' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Carlos Medina',
                'role' => 'Cliente frecuente',
                'rating' => 5,
                'text' => 'Pedí delivery y llegó rápido, caliente y con una presentación impecable.',
                'platform' => 'TripAdvisor',
                'date_literal' => 'Hace 1 mes',
                'is_verified' => true,
                'is_active' => true,
            ],
            [
                'name' => 'María Torres',
                'role' => 'Diseñadora',
                'rating' => 4,
                'text' => 'Muy buen ambiente para cenar en familia, volveremos pronto.',
                'platform' => 'Google Reviews',
                'date_literal' => 'Hace 3 días',
                'is_verified' => true,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                [
                    'name' => $testimonial['name'],
                    'platform' => $testimonial['platform'],
                ],
                $testimonial
            );
        }

        $galleryItems = [
            [
                'title' => 'Nuestra cocina',
                'description' => 'Preparación en vivo de nuestros platos estrella',
                'image_url' => 'https://images.unsplash.com/photo-1556911220-bda9f7f7597e?q=80&w=1200&auto=format&fit=crop',
                'span_type' => 'col-span-2 row-span-2',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Plato especial',
                'description' => 'Selección del chef',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200&auto=format&fit=crop',
                'span_type' => 'col-span-1 row-span-1',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Ambiente interior',
                'description' => 'Espacios cómodos y modernos',
                'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop',
                'span_type' => 'col-span-1 row-span-2',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Postres artesanales',
                'description' => 'Final perfecto para tu visita',
                'image_url' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=1200&auto=format&fit=crop',
                'span_type' => 'col-span-2 row-span-1',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($galleryItems as $item) {
            GalleryItem::updateOrCreate(
                ['order' => $item['order']],
                $item
            );
        }

        $restaurantValues = [
            [
                'title' => 'Calidad',
                'description' => 'Seleccionamos ingredientes frescos y procesos consistentes en cada plato.',
                'icon' => 'ShieldCheck',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Hospitalidad',
                'description' => 'Nos enfocamos en una atención cálida y cercana para cada cliente.',
                'icon' => 'Heart',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Innovación',
                'description' => 'Combinamos tradición y creatividad para una experiencia memorable.',
                'icon' => 'Sparkles',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($restaurantValues as $value) {
            RestaurantValue::updateOrCreate(
                ['title' => $value['title']],
                $value
            );
        }

        $this->command->info('Contenido web (testimonios, galería y valores) sembrado correctamente.');
    }
}
