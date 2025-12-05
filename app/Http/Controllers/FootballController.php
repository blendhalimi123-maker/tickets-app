<?php

namespace App\Http\Controllers;

use App\Services\FootballService;

class FootballController extends Controller
{
    public function schedule(FootballService $footballService)
    {
        $teamId = 83;
        $data = $footballService->getTeamSchedule($teamId);
        $matches = $data[0]['fixtures'] ?? [];

        return view('football.schedule', compact('matches'));
    }
}

