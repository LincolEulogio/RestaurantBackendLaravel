<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Administrador',
                'slug' => 'admin',
                'permissions' => ['dashboard' => true, 'orders' => true, 'menu' => true, 'inventory' => true, 'reports' => true, 'settings' => true],
            ],
            [
                'name' => 'Gerente',
                'slug' => 'manager',
                'permissions' => ['dashboard' => true, 'orders' => true, 'menu' => true, 'inventory' => true, 'reports' => true, 'settings' => false],
            ],
            [
                'name' => 'Chef',
                'slug' => 'chef',
                'permissions' => ['dashboard' => false, 'orders' => true, 'menu' => true, 'inventory' => true, 'reports' => false, 'settings' => false],
            ],
            [
                'name' => 'Mesero',
                'slug' => 'waiter',
                'permissions' => ['dashboard' => false, 'orders' => true, 'menu' => false, 'inventory' => false, 'reports' => false, 'settings' => false],
            ],
            [
                'name' => 'Cajero',
                'slug' => 'cashier',
                'permissions' => ['dashboard' => false, 'orders' => true, 'menu' => false, 'inventory' => false, 'reports' => true, 'settings' => false],
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
