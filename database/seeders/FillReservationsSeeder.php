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

        $baseDate = now()->addDay()->format('Y-m-d');

        foreach ($tables as $index => $table) {
            $time = now()->setTime(12, 0)->addMinutes($index * 30)->format('H:i:s');

            Reservation::updateOrCreate(
                [
                    'table_id' => $table->id,
                    'reservation_date' => $baseDate,
                    'reservation_time' => $time,
                ],
                [
                    'customer_name' => 'Reserva Demo '.($index + 1),
                    'customer_email' => 'reserva'.($index + 1).'@demo.com',
                    'customer_phone' => '90000000'.($index % 10),
                    'party_size' => min($table->capacity, 4),
                    'status' => 'confirmed',
                    'special_request' => 'Reserva de ejemplo para pruebas',
                ]
            );
        }
    }
}
