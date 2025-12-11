<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Super Admin: Acceso absoluto e incuestionable
        Role::updateOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Administrador',
                'permissions' => [
                    'dashboard' => true,
                    'orders' => true,
                    'kitchen' => true,
                    'tables' => true,
                    'reservations' => true,
                    'menu' => true,
                    'inventory' => true,
                    'reports' => true,
                    'billing' => true,
                    'blogs' => true,
                    'settings' => true,
                ]
            ]
        );

        // 1. Administrador: Acceso total (Administrador del restaurante)
        Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrador',
                'permissions' => [
                    'dashboard' => true,
                    'orders' => true,
                    'kitchen' => true,
                    'tables' => true,
                    'reservations' => true,
                    'menu' => true,
                    'inventory' => true,
                    'reports' => true,
                    'billing' => true,
                    'blogs' => true,
                    'settings' => true,
                ]
            ]
        );

        // 2. Gerente: Todo excepto settings críticos (opcional, aquí le damos casi todo)
        Role::updateOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Gerente',
                'permissions' => [
                    'dashboard' => true,
                    'orders' => true,
                    'kitchen' => true,
                    'tables' => true,
                    'reservations' => true,
                    'menu' => true,
                    'inventory' => true,
                    'reports' => true,
                    'billing' => true,
                    'blogs' => true,
                    'settings' => false, // No accede a config del sistema
                ]
            ]
        );

        // 3. Chef / Cocina: Solo cocina y ver pedidos, quizás inventario
        Role::updateOrCreate(
            ['slug' => 'chef'],
            [
                'name' => 'Chef de Cocina',
                'permissions' => [
                    'dashboard' => false,
                    'orders' => false, // Solo ve KDS, no gestiona pedidos
                    'kitchen' => true,
                    'tables' => false,
                    'reservations' => false,
                    'menu' => false,
                    'inventory' => true, // Puede ver stock para cocinar
                    'reports' => false,
                    'billing' => false,
                    'blogs' => false,
                    'settings' => false,
                ]
            ]
        );

        // 4. Mesero: Pedidos, Mesas, Reservas
        Role::updateOrCreate(
            ['slug' => 'waiter'],
            [
                'name' => 'Mesero',
                'permissions' => [
                    'dashboard' => true,
                    'orders' => true,
                    'kitchen' => false,
                    'tables' => true,
                    'reservations' => true,
                    'menu' => false, // No edita menú
                    'inventory' => false,
                    'reports' => false,
                    'billing' => false, // No cobra, solo pide (o true si cobra en mesa)
                    'blogs' => false,
                    'settings' => false,
                ]
            ]
        );

        // 5. Cajero: Facturación, Pedidos, Reportes (Cierre de caja)
        Role::updateOrCreate(
            ['slug' => 'cashier'],
            [
                'name' => 'Cajero',
                'permissions' => [
                    'dashboard' => true,
                    'orders' => true, // Puede crear pedidos mostrador
                    'kitchen' => false,
                    'tables' => true,
                    'reservations' => true,
                    'menu' => false,
                    'inventory' => false,
                    'reports' => true, // Reportes de ventas/caja
                    'billing' => true,
                    'blogs' => false,
                    'settings' => false,
                ]
            ]
        );
        
        // 6. Inventariador: Solo inventario
        Role::updateOrCreate(
            ['slug' => 'inventory_manager'],
            [
                'name' => 'Encargado de Inventario',
                'permissions' => [
                    'dashboard' => false,
                    'orders' => false,
                    'kitchen' => false,
                    'tables' => false,
                    'reservations' => false,
                    'menu' => false,
                    'inventory' => true,
                    'reports' => false,
                    'billing' => false,
                    'blogs' => false,
                    'settings' => false,
                ]
            ]
        );
        // 7. Content Manager: Blogs y Contenido Web
        Role::updateOrCreate(
            ['slug' => 'content_manager'],
            [
                'name' => 'Gestor de Contenido',
                'permissions' => [
                    'dashboard' => true,
                    'orders' => false,
                    'kitchen' => false,
                    'tables' => false,
                    'reservations' => false,
                    'menu' => true,    // Puede editar platos/menú q se muestra en web
                    'inventory' => false,
                    'reports' => false,
                    'billing' => false,
                    'blogs' => true,   // Principal funcion
                    'settings' => false,
                ]
            ]
        );
    }
}
