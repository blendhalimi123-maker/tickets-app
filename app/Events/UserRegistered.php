<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $adminData;

    public function __construct($user)
    {
        $this->message = "Welcome to our newest fan, {$user->name}!";

        $this->adminData = [
            'name' => $user->name,
            'email' => $user->email,
            'time' => now()->format('H:i:s')
        ];
    }

    public function broadcastOn()
    {
        return [new Channel('sales-channel')];
    }
}