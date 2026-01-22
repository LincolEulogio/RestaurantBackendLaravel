<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    if (!Schema::hasTable('inventory_items')) {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->default('unit');
            $table->decimal('stock_current', 10, 2)->default(0);
            $table->decimal('stock_min', 10, 2)->default(0);
            $table->timestamps();
        });
        echo "Table 'inventory_items' created successfully.\n";
    }

    if (!Schema::hasTable('product_inventory')) {
        Schema::create('product_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('inventory_item_id');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->timestamps();

            // Direct foreign keys often fail if tables aren't perfectly aligned, 
            // but we'll try or just index them for performance first
            $table->index('product_id');
            $table->index('inventory_item_id');
        });
        echo "Table 'product_inventory' created successfully.\n";
    } else {
        echo "Table 'product_inventory' already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
