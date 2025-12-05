<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Seat;
use App\Models\EventSeat;

class GenerateEventSeats extends Command
{
    protected $signature = 'app:generate-event-seats {event_id}';
    protected $description = 'Generate all seats for a given event automatically';

    public function handle()
    {
        $eventId = $this->argument('event_id');
        $event = Event::find($eventId);

        if (!$event) {
            $this->error("Event with ID {$eventId} not found.");
            return;
        }

        $this->info("Generating seats for event: {$event->title}");

        $seats = Seat::with('entry.stand')->get();

        foreach ($seats as $seat) {
            $standName = ucfirst(strtolower($seat->entry->stand->name));
            $price = match ($standName) {
                'East' => $event->east_price,
                'West' => $seat->type === 'vip' ? $event->west_vip_price : $event->west_price,
                'North' => $event->north_price,
                'South' => $event->south_price,
                default => 0,
            };

            EventSeat::create([
                'event_id' => $event->id,
                'seat_id' => $seat->id,
                'price' => $price,
                'status' => 'available',
            ]);
        }

        $this->info("All seats generated successfully!");
    }
}
