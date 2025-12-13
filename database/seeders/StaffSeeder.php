<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // 2. Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Restaurant',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 3. Cocineros (3 personas)
        $chefs = [
            ['name' => 'Chef Principal', 'email' => 'chef1@example.com'],
            ['name' => 'Ayudante Cocina 1', 'email' => 'chef2@example.com'],
            ['name' => 'Ayudante Cocina 2', 'email' => 'chef3@example.com'],
        ];

        foreach ($chefs as $chef) {
            User::updateOrCreate(
                ['email' => $chef['email']],
                [
                    'name' => $chef['name'],
                    'password' => Hash::make('password'),
                    'role' => 'cocinero',
                ]
            );
        }

        // 4. Meseros (5 personas)
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "mesero{$i}@example.com"],
                [
                    'name' => "Mesero {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'mesero',
                ]
            );
        }

        // 5. Cajeros (2 personas)
        $cashiers = [
            ['name' => 'Cajero Principal', 'email' => 'cajero1@example.com'],
            ['name' => 'Cajero Turno Tarde', 'email' => 'cajero2@example.com'],
        ];

        foreach ($cashiers as $cashier) {
            User::updateOrCreate(
                ['email' => $cashier['email']],
                [
                    'name' => $cashier['name'],
                    'password' => Hash::make('password'),
                    'role' => 'cajero',
                ]
            );
        }

        // 6. Delivery (2 personas)
        $delivery = [
            ['name' => 'Repartidor 1', 'email' => 'delivery1@example.com'],
            ['name' => 'Repartidor 2', 'email' => 'delivery2@example.com'],
        ];

        foreach ($delivery as $person) {
            User::updateOrCreate(
                ['email' => $person['email']],
                [
                    'name' => $person['name'],
                    'password' => Hash::make('password'),
                    'role' => 'delivery',
                ]
            );
        }
    }
}
