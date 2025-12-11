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
        Schema::table('orders', function (Blueprint $table) {
            // Add order_date field after order_number
            $table->timestamp('order_date')->nullable()->after('order_number');
            
            // Add index for better query performance
            $table->index('order_date');
        });

        // Populate order_date with created_at for existing orders
        DB::statement('UPDATE orders SET order_date = created_at WHERE order_date IS NULL');
        
        // Make order_date NOT NULL after populating
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('order_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_date']);
            $table->dropColumn('order_date');
        });
    }
};
