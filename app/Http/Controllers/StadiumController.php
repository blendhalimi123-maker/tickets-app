<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        
        return view('seat', compact('match'));
    }
    
    public function selectSeat(Request $request)
    {
        return back()->with('success', 'Seat selected!');
    }
}