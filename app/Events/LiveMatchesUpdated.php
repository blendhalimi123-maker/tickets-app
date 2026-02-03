<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveMatchesUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $matches;

    public string $generatedAt;

    /**
     * @param array<int, array<string, mixed>> $matches
     */
    public function __construct(array $matches, ?string $generatedAt = null)
    {
        $this->matches = $matches;
        $this->generatedAt = $generatedAt ?: now()->toIso8601String();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('football.live'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'LiveMatchesUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'generatedAt' => $this->generatedAt,
            'matches' => $this->matches,
        ];
    }
}

