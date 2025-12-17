<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameTicketController extends Controller
{
    public function manage($gameId)
    {
        $game = Game::firstOrCreate(
            ['api_game_id' => $gameId],
            [
                'title' => 'Match ' . $gameId,
                'home_team' => 'Home Team',
                'away_team' => 'Away Team',
                'stadium' => 'Main Stadium',
                'match_date' => now()->addDays(7),
            ]
        );
        
        return view('admin.manage-tickets', compact('game'));
    }
    
    public function update(Request $request, $gameId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'home_team' => 'required|string|max:100',
            'away_team' => 'required|string|max:100',
            'stadium' => 'required|string|max:150',
            'match_date' => 'required|date',
        ]);
        
        Game::updateOrCreate(
            ['api_game_id' => $gameId],
            $validated
        );
        
        return back()->with('success', 'Game details updated!');
    }
}