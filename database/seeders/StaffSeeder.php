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
        // 1. Super Admin (Único)
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // 2. Admin (Único)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Restaurant',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 3. Gerente (Único)
        User::updateOrCreate(
            ['email' => 'gerente@example.com'],
            [
                'name' => 'Gerente General',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]
        );

        // 4. Content Manager (Único)
        User::updateOrCreate(
            ['email' => 'content@example.com'],
            [
                'name' => 'Gestor Contenidos',
                'password' => Hash::make('password'),
                'role' => 'content_manager',
            ]
        );

        // 5. Encargado de Inventario (Único en este caso)
        User::updateOrCreate(
            ['email' => 'inventario@example.com'],
            [
                'name' => 'Jefe de Almacén',
                'password' => Hash::make('password'),
                'role' => 'inventory_manager',
            ]
        );

        // 6. Chefs (3 Personas)
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
                    'role' => 'chef',
                ]
            );
        }

        // 7. Meseros (5 Personas)
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "mesero{$i}@example.com"],
                [
                    'name' => "Mesero {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'waiter',
                ]
            );
        }

        // 8. Cajeros (2 Personas)
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
                    'role' => 'cashier',
                ]
            );
        }
    }
}
