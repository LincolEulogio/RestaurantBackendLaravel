<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enums are hard to change with standard Laravel blueprint in some DBs
        // We'll use a direct DB statement for MySQL/MariaDB or just leave it for SQLite (which ignores enum constraints)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_type ENUM('delivery', 'pickup', 'dine-in', 'online') DEFAULT 'pickup'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_type ENUM('delivery', 'pickup', 'dine-in') DEFAULT 'pickup'");
        }
    }
};
