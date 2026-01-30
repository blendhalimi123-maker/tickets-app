<?php

namespace App\Events;

use App\Models\Game; 
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
    public $gameName;

    public function __construct(Game $game, int $soldQuantity = 1)
    {
        $this->gameId = $game->api_game_id;
        
        $this->ticketsLeft = max(0, $game->total_tickets - $game->tickets_sold);
        
        $this->gameName = "{$game->home_team} vs {$game->away_team}";

        if ($this->ticketsLeft <= 0) {
            $this->message = "Sold out! No more tickets for {$this->gameName}.";
        } else {
            $this->message = "Update: {$this->ticketsLeft} tickets left for {$this->gameName}.";
        }
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('game.' . $this->gameId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'GameTicketSold';
    }

    public function broadcastWith(): array
    {
        return [
            'gameId' => $this->gameId,
            'gameName' => $this->gameName,
            'message' => $this->message,
            'ticketsLeft' => $this->ticketsLeft,
        ];
    }
}