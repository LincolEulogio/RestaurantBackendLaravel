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
        Schema::table('tables', function (Blueprint $table) {
            $table->unsignedBigInteger('current_session_id')->nullable()->after('status');
            // Assuming we might want a foreign key later, but for now just the column as integer is safer if sessions can be deleted or if circular dependency issues arise. 
            // Ideally: $table->foreign('current_session_id')->references('id')->on('table_sessions')->onDelete('set null');
            // But if table_sessions also has table_id, be careful. 
            // Let's stick to simple column or foreign key if we are sure table_sessions table exists (it does).
            // Let's add partial constraint if possible, or just integer.
            // Given previous error, it expects the column.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('current_session_id');
        });
    }
};
