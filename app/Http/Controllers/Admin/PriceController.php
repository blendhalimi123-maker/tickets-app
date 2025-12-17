<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function manage($gameId)
    {
        $gameData = cache()->get("game_{$gameId}", [
            'title' => "Match {$gameId}",
            'home_team' => 'Home Team',
            'away_team' => 'Away Team',
            'stadium' => 'Main Stadium',
            'match_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
        ]);
        
        $prices = cache()->get("prices_{$gameId}", [
            'category1' => 85.00,
            'category2' => 65.00,
            'category3' => 45.00,
            'category4' => 35.00,
        ]);
        
        return view('admin.tickets-events.manage', compact('gameId', 'gameData', 'prices'));
    }
    
    public function update(Request $request, $gameId)
    {
        $gameData = [
            'title' => $request->title,
            'home_team' => $request->home_team,
            'away_team' => $request->away_team,
            'stadium' => $request->stadium,
            'match_date' => $request->match_date,
        ];
        
        cache()->put("game_{$gameId}", $gameData, now()->addDays(30));
        
        $prices = [
            'category1' => $request->category1,
            'category2' => $request->category2,
            'category3' => $request->category3,
            'category4' => $request->category4,
        ];
        
        cache()->put("prices_{$gameId}", $prices, now()->addDays(30));
        
        return back()->with('success', 'Game details and prices updated!');
    }
}