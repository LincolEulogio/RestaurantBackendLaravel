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
        Schema::create('delivery_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount_received', 10, 2);
            $table->decimal('change_given', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('delivery_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_payments');
    }
};
