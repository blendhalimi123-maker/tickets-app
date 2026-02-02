<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;

class FavoriteController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['favorites' => []]);
            }
            return redirect()->route('login');
        }

        if ($request->wantsJson()) {
            $favorites = $user->favorites()->pluck('api_game_id');
            return response()->json(['favorites' => $favorites]);
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

        $existing = Game::where('api_game_id', $apiGameId)->first();

        $home = $request->input('home_team') ?: ($existing->home_team ?? null);
        $away = $request->input('away_team') ?: ($existing->away_team ?? null);

        $title = null;
        if ($home || $away) {
            $title = trim(($home ?? '') . ' vs ' . ($away ?? ''));
        } else {
            $title = $existing->title ?? null;
        }

        $game = Game::updateOrCreate(
            ['api_game_id' => $apiGameId],
            [
                'title'          => $title,
                'home_team'      => $home,
                'away_team'      => $away,
                'home_team_logo' => $request->input('home_logo') ?: ($existing->home_team_logo ?? null),
                'away_team_logo' => $request->input('away_logo') ?: ($existing->away_team_logo ?? null),
                'match_date'     => $request->input('match_date') ?: ($existing->match_date ?? null),
                'match_time'     => $request->input('match_time') ?: ($existing->match_time ?? null),
            ]
        );

        // Ensure title persisted
        if ($title) {
            $game->title = $title;
            $game->save();
        }

        if ($user->hasFavorited($apiGameId)) {
            $user->unfavorite($apiGameId);
            return response()->json(['status' => 'unfavorited', 'id' => $apiGameId]);
        }

        $user->favorite($apiGameId);

        $gameObj = [
            'id' => $game->api_game_id,
            'title' => $game->title,
            'date' => $game->match_date ? $game->match_date->format('M j, Y') : null,
            'time' => $game->match_date ? $game->match_date->format('g:i A') : $game->match_time,
            'homeTeam' => ['name' => $game->home_team, 'logo' => $game->home_team_logo ?? null],
            'awayTeam' => ['name' => $game->away_team, 'logo' => $game->away_team_logo ?? null],
        ];

        return response()->json(['status' => 'favorited', 'game' => $gameObj]);
    }
}