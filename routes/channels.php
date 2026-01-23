<?php

use Illuminate\Broadcasting\Broadcast;
use Illuminate\Support\Facades\Broadcast as BroadcastFacade;

/**
 * Here you may register all of the event broadcasting channels that your
 * application supports. The given channel authorization callbacks are
 * used to check if an authenticated user can listen to the channel.
 */

BroadcastFacade::channel('admin-channel', function ($user) {
    return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
});

// Allow public sales channel (no auth required) for simple broadcasts
BroadcastFacade::channel('sales-channel', function ($user = null) {
    return true;
});
