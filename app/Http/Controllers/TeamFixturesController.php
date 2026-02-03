<?php

namespace App\Http\Controllers;

use App\Services\FootballService;
use Illuminate\Http\Request;

class TeamFixturesController extends Controller
{
    public function __construct(private readonly FootballService $footballService)
    {
    }

    public function show(Request $request, string $teamId)
    {
        $teamInfo = $this->footballService->getTeamInfo($teamId) ?: [];
        $matchesData = $this->footballService->getTeamMatches($teamId);

        $matches = $matchesData['matches'] ?? [];
        usort($matches, static function (array $a, array $b): int {
            $aDate = $a['utcDate'] ?? $a['match_date'] ?? $a['date'] ?? null;
            $bDate = $b['utcDate'] ?? $b['match_date'] ?? $b['date'] ?? null;
            return strcmp((string) $aDate, (string) $bDate);
        });

        return view('football.team-fixtures', [
            'teamId' => $teamId,
            'teamInfo' => $teamInfo,
            'matches' => $matches,
        ]);
    }
}

