<?php

use App\Models\Game;
use Illuminate\Support\Facades\Broadcast;


 
Broadcast::channel('admin.notifications', function ($user) {
    return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
});


Broadcast::channel('game.{gameId}', function ($user, $gameId) {
    if (!$user) {
        return false;
    }

 
    return $user->favorites()
                ->where('api_game_id', $gameId)
                ->exists();
});