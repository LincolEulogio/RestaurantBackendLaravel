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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('image_url')->nullable();
            $table->string('image_public_id')->nullable();
            $table->string('name');
            $table->string('role')->nullable();
            $table->integer('rating')->default(5);
            $table->text('text');
            $table->string('platform')->default('Google Reviews');
            $table->string('date_literal')->nullable(); // e.g. "Hace 2 semanas"
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
