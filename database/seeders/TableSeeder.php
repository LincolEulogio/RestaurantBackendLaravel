<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Indoor Tables
        $tables = [
            ['table_number' => 'T1', 'capacity' => 2, 'location' => 'Indoor', 'status' => 'available'],
            ['table_number' => 'T2', 'capacity' => 2, 'location' => 'Indoor', 'status' => 'available'],
            ['table_number' => 'T3', 'capacity' => 4, 'location' => 'Indoor', 'status' => 'available'],
            ['table_number' => 'T4', 'capacity' => 4, 'location' => 'Indoor', 'status' => 'available'],
            ['table_number' => 'T5', 'capacity' => 6, 'location' => 'Indoor', 'status' => 'available'],
            // Outdoor Tables
            ['table_number' => 'O1', 'capacity' => 2, 'location' => 'Outdoor', 'status' => 'available'],
            ['table_number' => 'O2', 'capacity' => 4, 'location' => 'Outdoor', 'status' => 'available'],
            ['table_number' => 'O3', 'capacity' => 4, 'location' => 'Outdoor', 'status' => 'available'],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
