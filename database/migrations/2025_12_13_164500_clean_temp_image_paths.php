<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clean up products with temporary file paths
        DB::table('products')
            ->where('image_url', 'LIKE', '%tmp%')
            ->update([
                'image_url' => null,
                'image_public_id' => null,
            ]);
            
        // Clean up blogs with temporary file paths
        DB::table('blogs')
            ->where('image_url', 'LIKE', '%tmp%')
            ->update([
                'image_url' => null,
                'image_public_id' => null,
            ]);
            
        // Clean up promotions with temporary file paths
        DB::table('promotions')
            ->where('image_url', 'LIKE', '%tmp%')
            ->update([
                'image_url' => null,
                'image_public_id' => null,
            ]);
    }

    public function down(): void
    {
        // No need to rollback
    }
};
