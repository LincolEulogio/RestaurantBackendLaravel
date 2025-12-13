<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Database\Seeder;

class FillReservationsSeeder extends Seeder
{
    public function run()
    {
        // Get all tables suitable for 2 people
        $tables = Table::where('capacity', '>=', 2)->get();

        foreach ($tables as $table) {
            Reservation::create([
                'table_id' => $table->id,
                'customer_name' => 'System Blocker',
                'customer_email' => 'blocker@system.com',
                'customer_phone' => '0000000000',
                'reservation_date' => '2025-12-10',
                'reservation_time' => '11:00:00',
                'party_size' => 2,
                'status' => 'confirmed',
                'special_request' => 'Blocked for testing',
            ]);
        }
    }
}
