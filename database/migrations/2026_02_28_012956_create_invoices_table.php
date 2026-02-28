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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique(); // e.g. B001-000001
            $table->enum('invoice_type', ['boleta', 'factura', 'ticket'])->default('boleta');
            
            // Customer Details for the Invoice
            $table->string('customer_name')->nullable();
            $table->string('customer_document_type')->nullable(); // DNI, RUC, CE
            $table->string('customer_document_number')->nullable();
            $table->string('customer_address')->nullable();
            
            // Financials (denormalized for the invoice record)
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            
            $table->string('status')->default('paid'); // paid, cancelled
            $table->string('pdf_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
