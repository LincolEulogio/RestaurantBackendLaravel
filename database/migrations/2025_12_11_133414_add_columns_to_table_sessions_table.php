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
        Schema::table('table_sessions', function (Blueprint $table) {
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->foreignId('waiter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_token')->unique();
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_sessions', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['waiter_id']);
            $table->dropColumn([
                'table_id',
                'waiter_id',
                'session_token',
                'status',
                'started_at',
                'ended_at',
                'total_amount',
            ]);
        });
    }
};
