<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Change to VARCHAR temporarily
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` VARCHAR(50) DEFAULT 'pending'");
        
        // Step 2: Change back to ENUM with new values
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered', 'cancelled', 'served', 'paid') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Change to VARCHAR temporarily
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` VARCHAR(50) DEFAULT 'pending'");
        
        // Step 2: Revert back to original ENUM
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled', 'served', 'paid') DEFAULT 'pending'");
    }
};
