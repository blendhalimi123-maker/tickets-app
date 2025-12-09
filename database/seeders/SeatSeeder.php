<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $rows = range('A', 'T'); 
        $seats_per_row = 20;

        foreach ($rows as $row) {
            for ($i = 1; $i <= $seats_per_row; $i++) {
                Seat::create([
                    'fixture_id' => 1, 
                    'row' => $row,
                    'number' => $i,
                    'is_booked' => false,
                ]);
            }
        }
    }
}
