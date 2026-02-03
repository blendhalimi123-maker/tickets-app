<?php

namespace App\Http\Controllers;

use App\Services\FootballService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(private readonly FootballService $footballService)
    {
    }

    public function show(Request $request, string $matchId)
    {
        $sportMonks = $this->footballService->getSportMonksFixture($matchId);
        if ($sportMonks) {
            return view('football.match', [
                'matchId' => $matchId,
                'source' => 'sportmonks',
                'match' => $sportMonks,
            ]);
        }

        $footballData = $this->footballService->getMatchById($matchId);
        return view('football.match', [
            'matchId' => $matchId,
            'source' => $footballData ? 'football-data' : null,
            'match' => $footballData,
        ]);
    }
}

