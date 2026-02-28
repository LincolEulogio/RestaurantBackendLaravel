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
            $table->enum('billing_type', ['boleta', 'factura'])->default('boleta')->after('payment_method');
            $table->string('business_name')->nullable()->after('billing_type');
            $table->string('ruc', 11)->nullable()->after('business_name');
            $table->string('fiscal_address')->nullable()->after('ruc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['billing_type', 'business_name', 'ruc', 'fiscal_address']);
        });
    }
};
