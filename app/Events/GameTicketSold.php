<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameTicketSold implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameId;
    public $message;
    public $ticketsLeft;

    public function __construct($game, int $soldQuantity = 1)
    {
        $this->gameId = $game->api_game_id;
        $this->ticketsLeft = max(0, $game->total_tickets - $game->tickets_sold);

        if ($this->ticketsLeft <= 0) {
            $this->message = 'Sold out';
        } else {
            $this->message = "A ticket has been sold ({$soldQuantity}). Tickets left: {$this->ticketsLeft}";
        }
    }

    public function broadcastOn()
    {
        return new PrivateChannel('game.' . $this->gameId);
    }

    public function broadcastAs()
    {
        return 'GameTicketSold';
    }

    public function broadcastWith()
    {
        return [
            'gameId' => $this->gameId,
            'message' => $this->message,
            'ticketsLeft' => $this->ticketsLeft,
        ];
    }
}
