<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;

class FavoriteController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $favorites = $user->favorites()->get();

        return view('favorites.index', compact('favorites'));
    }

   
    public function toggle(Request $request, $apiGameId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $game = Game::updateOrCreate(
            ['api_game_id' => $apiGameId],
            [
                'title'          => $request->input('home_team') . ' vs ' . $request->input('away_team'),
                'home_team_logo' => $request->input('home_logo'),
                'away_team_logo' => $request->input('away_logo'),
                'match_date'     => $request->input('match_date'),
                'match_time'     => $request->input('match_time'),
            ]
        );

        if ($user->hasFavorited($apiGameId)) {
            $user->unfavorite($apiGameId);
            return response()->json(['status' => 'unfavorited']);
        }

        $user->favorite($apiGameId);
        return response()->json(['status' => 'favorited']);
    }
}