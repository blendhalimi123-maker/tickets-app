<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.notifications', function ($user) {
    return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
});

Broadcast::channel('game.{gameId}', function ($user, $gameId) {
    // Allow only users who favorited this game (by api_game_id) to subscribe
    if (! $user) return false;
    return $user->favorites()->where('api_game_id', $gameId)->exists();
});
