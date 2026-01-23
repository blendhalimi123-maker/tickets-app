<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketPurchased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adminData;

   
    public function __construct($cartItems, $user, $total)
    {
        $first = $cartItems->first();

        $match = null;
        if ($first) {
            $match = trim((($first->home_team ?? '') . ' v ' . ($first->away_team ?? '')));
            if (!empty($first->match_date)) {
                $match .= ' on ' . optional($first->match_date)->format('Y-m-d');
            }
        }

        $this->adminData = [
            'match' => $match ?: 'Unknown match',
            'customer' => $user?->name ?: 'Guest',
            'total' => $total,
        ];

        
        Log::info('TicketPurchased event created', ['adminData' => $this->adminData]);
    }

   
     
    public function broadcastOn()
    {
        return [new PrivateChannel('admin-channel')];
    }
}
