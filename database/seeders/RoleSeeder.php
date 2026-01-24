<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'superadmin',
                'permissions' => json_encode([
                    'dashboard' => true,
                    'orders' => true,
                    'kitchen' => true,
                    'billing' => true,
                    'reservations' => true,
                    'tables' => true,
                    'menu' => true,
                    'categories' => true,
                    'promotions' => true,
                    'blogs' => true,
                    'inventory' => true,
                    'billing_reports' => true,
                    'reports' => true,
                    'customers' => true,
                    'users' => true,
                    'roles' => true,
                    'settings' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Administrador',
                'slug' => 'admin',
                'permissions' => json_encode([
                    'dashboard' => true,
                    'orders' => true,
                    'kitchen' => true,
                    'billing' => true,
                    'reservations' => true,
                    'tables' => true,
                    'menu' => true,
                    'categories' => true,
                    'promotions' => true,
                    'blogs' => true,
                    'inventory' => true,
                    'billing_reports' => true,
                    'reports' => true,
                    'customers' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cajero',
                'slug' => 'cajero',
                'permissions' => json_encode([
                    'billing' => true,
                    'orders' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mesero',
                'slug' => 'mesero',
                'permissions' => json_encode([
                    'orders' => true,
                    'tables' => true,
                    'reservations' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cocinero',
                'slug' => 'cocinero',
                'permissions' => json_encode([
                    'kitchen' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Delivery',
                'slug' => 'delivery',
                'permissions' => json_encode([
                    'billing' => true,
                    'orders' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Delete existing roles first
        DB::table('roles')->truncate();
        
        DB::table('roles')->insert($roles);
    }
}
