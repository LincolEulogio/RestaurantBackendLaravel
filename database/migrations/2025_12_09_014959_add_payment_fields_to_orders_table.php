<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_lastname')->nullable()->after('customer_name');
            $table->string('customer_dni', 20)->nullable()->after('customer_lastname');
            $table->enum('payment_method', ['card', 'yape', 'plin', 'cash'])->nullable()->after('order_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_lastname', 'customer_dni', 'payment_method']);
        });
    }
};
