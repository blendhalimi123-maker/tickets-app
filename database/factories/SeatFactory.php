<?php

namespace Database\Factories;

use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    protected $model = Seat::class;

    public function definition(): array
    {
        return [
            // row and number will be generated manually in Seeder
            'fixture_id' => 1,
            'row' => 'A',
            'number' => 1,
            'is_booked' => false,
        ];
    }
}
