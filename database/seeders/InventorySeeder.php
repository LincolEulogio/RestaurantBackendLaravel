<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryItem;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Aceite de Oliva Extra Virgen',
                'sku' => 'OIL-001',
                'category' => 'Abarrotes',
                'stock_current' => 25.5,
                'stock_min' => 10.0,
                'unit' => 'lt',
                'price_unit' => 45.00,
                'is_active' => true,
            ],
            [
                'name' => 'Arroz Extra',
                'sku' => 'RICE-001',
                'category' => 'Abarrotes',
                'stock_current' => 100.0,
                'stock_min' => 20.0,
                'unit' => 'kg',
                'price_unit' => 4.50,
                'is_active' => true,
            ],
            [
                'name' => 'Pechuga de Pollo',
                'sku' => 'CHICKEN-001',
                'category' => 'Carnes',
                'stock_current' => 15.0,
                'stock_min' => 5.0,
                'unit' => 'kg',
                'price_unit' => 18.00,
                'is_active' => true,
            ],
            [
                'name' => 'Lomo Fino',
                'sku' => 'MEAT-001',
                'category' => 'Carnes',
                'stock_current' => 10.0,
                'stock_min' => 3.0,
                'unit' => 'kg',
                'price_unit' => 45.00,
                'is_active' => true,
            ],
            [
                'name' => 'Tomate Italiano',
                'sku' => 'VEG-001',
                'category' => 'Verduras',
                'stock_current' => 12.0,
                'stock_min' => 4.0,
                'unit' => 'kg',
                'price_unit' => 3.50,
                'is_active' => true,
            ],
            [
                'name' => 'Lechuga Americana',
                'sku' => 'VEG-002',
                'category' => 'Verduras',
                'stock_current' => 15.0,
                'stock_min' => 5.0,
                'unit' => 'unid',
                'price_unit' => 2.50,
                'is_active' => true,
            ],
            [
                'name' => 'Queso Mozzarella',
                'sku' => 'DAIRY-001',
                'category' => 'Lácteos',
                'stock_current' => 8.5,
                'stock_min' => 2.0,
                'unit' => 'kg',
                'price_unit' => 28.00,
                'is_active' => true,
            ],
            [
                'name' => 'Huevos Pardos',
                'sku' => 'DAIRY-002',
                'category' => 'Lácteos',
                'stock_current' => 120.0,
                'stock_min' => 30.0,
                'unit' => 'unid',
                'price_unit' => 0.60,
                'is_active' => true,
            ],
            [
                'name' => 'Harina sin preparar',
                'sku' => 'FLOUR-001',
                'category' => 'Abarrotes',
                'stock_current' => 50.0,
                'stock_min' => 10.0,
                'unit' => 'kg',
                'price_unit' => 3.20,
                'is_active' => true,
            ],
            [
                'name' => 'Azúcar Rubia',
                'sku' => 'SUGAR-001',
                'category' => 'Abarrotes',
                'stock_current' => 30.0,
                'stock_min' => 8.0,
                'unit' => 'kg',
                'price_unit' => 3.80,
                'is_active' => true,
            ],
            [
                'name' => 'Papas Amarillas',
                'sku' => 'VEG-003',
                'category' => 'Verduras',
                'stock_current' => 40.0,
                'stock_min' => 10.0,
                'unit' => 'kg',
                'price_unit' => 4.50,
                'is_active' => true,
            ],
            [
                'name' => 'Cebolla Roja',
                'sku' => 'VEG-004',
                'category' => 'Verduras',
                'stock_current' => 20.0,
                'stock_min' => 5.0,
                'unit' => 'kg',
                'price_unit' => 2.80,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(
                ['sku' => $item['sku']], // Check by SKU to avoid duplicates
                $item
            );
        }
    }
}
