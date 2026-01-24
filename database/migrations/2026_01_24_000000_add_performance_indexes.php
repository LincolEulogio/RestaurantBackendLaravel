<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to orders table for frequently queried columns
        // Using raw SQL to avoid Doctrine issues
        try {
            DB::statement('CREATE INDEX IF NOT EXISTS orders_status_index ON orders(status)');
            DB::statement('CREATE INDEX IF NOT EXISTS orders_order_source_index ON orders(order_source)');
            DB::statement('CREATE INDEX IF NOT EXISTS orders_payment_status_index ON orders(payment_status)');
            DB::statement('CREATE INDEX IF NOT EXISTS orders_created_at_index ON orders(created_at)');
            DB::statement('CREATE INDEX IF NOT EXISTS orders_order_date_index ON orders(order_date)');
            DB::statement('CREATE INDEX IF NOT EXISTS orders_status_created_at_index ON orders(status, created_at)');
        } catch (\Exception $e) {
            // Indexes may already exist, continue
        }

        // Add indexes to products table
        try {
            DB::statement('CREATE INDEX IF NOT EXISTS products_is_available_index ON products(is_available)');
            DB::statement('CREATE INDEX IF NOT EXISTS products_category_available_index ON products(category_id, is_available)');
        } catch (\Exception $e) {
            // Indexes may already exist, continue
        }

        // Add indexes to reservations table
        try {
            DB::statement('CREATE INDEX IF NOT EXISTS reservations_status_index ON reservations(status)');
            DB::statement('CREATE INDEX IF NOT EXISTS reservations_date_index ON reservations(reservation_date)');
            DB::statement('CREATE INDEX IF NOT EXISTS reservations_date_status_index ON reservations(reservation_date, status)');
        } catch (\Exception $e) {
            // Indexes may already exist, continue
        }

        // Add indexes to tables table
        try {
            DB::statement('CREATE INDEX IF NOT EXISTS tables_status_index ON tables(status)');
        } catch (\Exception $e) {
            // Indexes may already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('DROP INDEX IF EXISTS orders_status_index');
            DB::statement('DROP INDEX IF EXISTS orders_order_source_index');
            DB::statement('DROP INDEX IF EXISTS orders_payment_status_index');
            DB::statement('DROP INDEX IF EXISTS orders_created_at_index');
            DB::statement('DROP INDEX IF EXISTS orders_order_date_index');
            DB::statement('DROP INDEX IF EXISTS orders_status_created_at_index');

            DB::statement('DROP INDEX IF EXISTS products_is_available_index');
            DB::statement('DROP INDEX IF EXISTS products_category_available_index');

            DB::statement('DROP INDEX IF EXISTS reservations_status_index');
            DB::statement('DROP INDEX IF EXISTS reservations_date_index');
            DB::statement('DROP INDEX IF EXISTS reservations_date_status_index');

            DB::statement('DROP INDEX IF EXISTS tables_status_index');
        } catch (\Exception $e) {
            // Ignore errors on rollback
        }
    }
};
