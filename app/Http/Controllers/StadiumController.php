<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCart;

class StadiumController extends Controller
{
    public function show($fixture_id)
    {
        $match = [
            'id' => $fixture_id,
            'team1' => 'Home Team',
            'team2' => 'Away Team',
            'match_date' => now()->addDays(7),
            'stadium' => 'Main Stadium'
        ];

        // Build a list of sold / reserved seats for this fixture so they
        // can be rendered as unavailable in the seat map.
        $soldSeats = GameCart::where('api_game_id', $fixture_id)
            ->whereIn('status', ['in_cart', 'paid'])
            ->get()
            ->map(function ($item) {
                // Frontend seat IDs follow the pattern: stand_row_number
                // Example: "north_A_1"
                return strtolower($item->stand) . '_' . $item->row . '_' . $item->seat_number;
            })
            ->toArray();
        
        return view('seat', compact('match', 'soldSeats'));
    }
    
    public function selectSeat(Request $request)
    {
        return back()->with('success', 'Seat selected!');
    }
}