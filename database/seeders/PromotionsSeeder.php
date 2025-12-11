<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use App\Models\Product;
use Carbon\Carbon;

class PromotionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some products
        $products = Product::all();
        
        if ($products->count() === 0) {
            $this->command->info('No products found, skipping promotion seeding.');
            return;
        }

        // 1. Combo Familiar (20% off)
        $promo1 = Promotion::create([
            'title' => 'Combo Familiar',
            'description' => 'Disfruta de una cena completa con nuestra selección especial.',
            'discount_percent' => 20,
            'image' => 'https://images.unsplash.com/photo-1544025162-d7669d62892?q=80&w=600&auto=format&fit=crop',
            'status' => true,
            'badge_label' => 'Popular',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        // Attach random products (take 2)
        $promo1->products()->attach($products->random(min(3, $products->count()))->pluck('id'));

        // 2. Oferta Fin de Semana (15% off)
        $promo2 = Promotion::create([
            'title' => 'Oferta Fin de Semana',
            'description' => 'Todo lo que necesitas para tu reunión.',
            'discount_percent' => 15,
            'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=600&auto=format&fit=crop',
            'status' => true,
            'badge_label' => 'Nuevo',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addWeeks(2),
        ]);

        // Attach random products (take 3 different ones)
        $promo2->products()->attach($products->random(min(4, $products->count()))->pluck('id'));

        // 3. Lunch Special (10% off) - Expiring soon
        $promo3 = Promotion::create([
            'title' => 'Almuerzo Ejecutivo',
            'description' => 'La mejor opción para tu break del mediodía.',
            'discount_percent' => 10,
            'image' => 'https://images.unsplash.com/photo-1576867757603-05b134ebc379?q=80&w=600&auto=format&fit=crop',
            'status' => true,
            'badge_label' => 'Limitado',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(2),
        ]);
        
        $promo3->products()->attach($products->random(min(2, $products->count()))->pluck('id'));
    }
}
