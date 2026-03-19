<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->command->info('🌱 Seeding database...');

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            StaffSeeder::class,
            SettingsSeeder::class,
            PaymentMethodSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PromotionsSeeder::class,
            BlogSeeder::class,
            InventorySeeder::class,
            TableSeeder::class,
            FillReservationsSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            WebsiteContentSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}
