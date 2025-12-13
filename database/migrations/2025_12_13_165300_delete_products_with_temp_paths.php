<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Delete all products with temporary file paths
        DB::table('products')
            ->where('image_url', 'LIKE', '%tmp%')
            ->orWhere('image_url', 'LIKE', '%C:/%')
            ->orWhereNull('image_url')
            ->delete();
    }

    public function down(): void
    {
        // No rollback needed
    }
};
