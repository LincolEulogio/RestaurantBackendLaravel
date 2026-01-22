<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logFile = __DIR__ . '/db_fix_log.txt';
function logMsg($msg) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $msg . PHP_EOL, FILE_APPEND);
}

logMsg("Starting DB Fix Script");

try {
    $dbName = DB::connection()->getDatabaseName();
    logMsg("Connected to Database: " . $dbName);

    if (!Schema::hasTable('inventory_items')) {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->default('unit');
            $table->decimal('stock_current', 10, 2)->default(0);
            $table->decimal('stock_min', 10, 2)->default(0);
            $table->timestamps();
        });
        logMsg("Table 'inventory_items' created successfully.");
    } else {
        logMsg("Table 'inventory_items' already exists.");
    }

    if (!Schema::hasTable('product_inventory')) {
        Schema::create('product_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('inventory_item_id');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->timestamps();
            $table->index('product_id');
            $table->index('inventory_item_id');
        });
        logMsg("Table 'product_inventory' created successfully.");
    } else {
        logMsg("Table 'product_inventory' already exists.");
    }

    logMsg("DB Fix Script Finished Successfully");
} catch (\Exception $e) {
    logMsg("ERROR: " . $e->getMessage());
}
