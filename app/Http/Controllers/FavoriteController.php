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

        $home = $request->input('home_team');
        $away = $request->input('away_team');

        $title = trim(($home ?? '') . ' vs ' . ($away ?? ''));
        if ($title === 'vs') {
            $title = $existing?->title ?? ('Match ' . $apiGameId);
        }

        $matchDate = $existing?->match_date;
        if ($request->filled('match_date')) {
            try {
                $matchDate = \Carbon\Carbon::parse($request->input('match_date'));
            } catch (\Throwable $e) {
                $matchDate = $existing?->match_date;
            }
        }

        $game = Game::updateOrCreate(
            ['api_game_id' => $apiGameId],
            [
                'title' => $title,
                'match_date' => $matchDate,
            ]
        );

        if ($user->hasFavorited($apiGameId)) {
            $user->unfavorite($apiGameId);
            return response()->json(['status' => 'unfavorited', 'id' => $apiGameId]);
        }

        $user->favorite($apiGameId);

        $gameObj = [
            'id' => $game->api_game_id,
            'title' => $game->title,
            'date' => $game->match_date ? $game->match_date->format('M j, Y') : null,
            'time' => $game->match_date ? $game->match_date->format('g:i A') : null,
            'homeTeam' => ['name' => $home, 'logo' => null],
            'awayTeam' => ['name' => $away, 'logo' => null],
        ];

        return response()->json(['status' => 'favorited', 'game' => $gameObj]);
    }
}
