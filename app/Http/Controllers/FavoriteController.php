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
        if (! $user) return response()->json(['favorites' => []]);

        $apiIds = $user->favorites()->pluck('api_game_id')->toArray();
        return response()->json(['favorites' => $apiIds]);
    }

    public function toggle($apiGameId)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $game = Game::firstOrCreate(['api_game_id' => $apiGameId], ['title' => 'Match ' . $apiGameId]);

        if ($user->hasFavorited($apiGameId)) {
            $user->unfavorite($apiGameId);
            return response()->json(['status' => 'unfavorited']);
        }

        $user->favorite($apiGameId);
        return response()->json(['status' => 'favorited']);
    }
}
