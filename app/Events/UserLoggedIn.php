<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $payload;

    public function __construct($user)
    {
        $this->payload = [
            'name' => $user->name,
            'email' => $user->email,
            'time' => now()->format('H:i:s'),
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin.notifications');
    }
}

