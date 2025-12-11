<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            // Change status to string to support flexible statuses. 
            // This requires doctrine/dbal for SQLite/MySQL cross-compatibility in older/some setups,
            // but is the standard Laravel way.
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            // Revert to enum would require knowing the exact previous state/enum definition
            // For now, we keep it as string or try to revert if absolutely necessary.
            // $table->enum('status', ['available', 'reserved', 'maintenance'])->change();
        });
    }
};
