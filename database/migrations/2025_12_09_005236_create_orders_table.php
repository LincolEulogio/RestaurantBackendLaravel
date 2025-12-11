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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name')->nullable(); // Made nullable for waiter/QR orders
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable(); // Made nullable
            $table->text('delivery_address')->nullable();
            $table->enum('order_type', ['delivery', 'pickup', 'dine-in'])->default('pickup');
            
            // New fields for Dine-in
            $table->foreignId('table_id')->nullable(); // Removed constrained() to avoid migration order issues
            $table->foreignId('waiter_id')->nullable(); // Removed constrained()
            $table->string('order_source')->default('web'); // web, waiter, qr
            $table->string('session_token')->nullable(); // Link to table session
            
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled', 'served', 'paid'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
