<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCart;
use App\Services\FootballService;

class StadiumController extends Controller
{
    protected FootballService $footballService;

    public function __construct(FootballService $footballService)
    {
        $this->footballService = $footballService;
    }

    public function show(Request $request, $fixture_id)
    {
        if ($request->has(['home', 'away', 'date', 'venue'])) {
            $match = [
                'id' => $fixture_id,
                'team1' => $request->query('home'),
                'team2' => $request->query('away'),
                'match_date' => $request->query('date'),
                'stadium' => $request->query('venue'),
            ];
        } else {
            $apiMatch = $this->footballService->getMatchById($fixture_id);

            if ($apiMatch) {
                $matchDate = $apiMatch['utcDate'] ?? now()->addDays(7)->toISOString();
                $match = [
                    'id' => $apiMatch['id'] ?? $fixture_id,
                    'team1' => $apiMatch['homeTeam']['shortName'] ?? $apiMatch['homeTeam']['name'] ?? 'Home Team',
                    'team2' => $apiMatch['awayTeam']['shortName'] ?? $apiMatch['awayTeam']['name'] ?? 'Away Team',
                    'match_date' => $matchDate,
                    'stadium' => $apiMatch['venue'] ?? ($apiMatch['area']['name'] ?? 'Stadium'),
                ];
            } else {
                $match = [
                    'id' => $fixture_id,
                    'team1' => 'Home Team',
                    'team2' => 'Away Team',
                    'match_date' => now()->addDays(7)->toISOString(),
                    'stadium' => 'Main Stadium'
                ];
            }
        }

       
        $soldSeats = GameCart::where('api_game_id', $fixture_id)
            ->whereIn('status', ['in_cart', 'paid'])
            ->get()
            ->map(function ($item) {
                
                return strtolower($item->stand) . '_' . $item->row . '_' . $item->seat_number;
            })
            ->toArray();
        
        return view('seat', compact('match', 'soldSeats'));
    }
    
    public function selectSeat(Request $request)
    {
        return back()->with('success', 'Seat selected!');
    }
}