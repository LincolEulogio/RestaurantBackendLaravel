<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Delete all products with invalid image paths
        DB::table('products')
            ->where(function($query) {
                $query->where('image_url', 'LIKE', '%tmp%')
                      ->orWhere('image_url', 'LIKE', '%C:/%')
                      ->orWhere('image_url', 'LIKE', '%file:///%')
                      ->orWhere('image_url', 'LIKE', '%storage/tmp%')
                      ->orWhere('image_url', 'LIKE', '%/storage/products%')
                      ->orWhereNull('image_url')
                      ->orWhere('image_url', '');
            })
            ->delete();
            
        // Delete all blogs with invalid image paths
        DB::table('blogs')
            ->where(function($query) {
                $query->where('image_url', 'LIKE', '%tmp%')
                      ->orWhere('image_url', 'LIKE', '%C:/%')
                      ->orWhere('image_url', 'LIKE', '%file:///%')
                      ->orWhere('image_url', 'LIKE', '%storage/tmp%')
                      ->orWhereNull('image_url')
                      ->orWhere('image_url', '');
            })
            ->delete();
            
        // Delete all promotions with invalid image paths
        DB::table('promotions')
            ->where(function($query) {
                $query->where('image_url', 'LIKE', '%tmp%')
                      ->orWhere('image_url', 'LIKE', '%C:/%')
                      ->orWhere('image_url', 'LIKE', '%file:///%')
                      ->orWhere('image_url', 'LIKE', '%storage/tmp%')
                      ->orWhereNull('image_url')
                      ->orWhere('image_url', '');
            })
            ->delete();
    }

    public function down(): void
    {
        // No rollback needed
    }
};
