<?php

namespace App\Http\Controllers;

use App\Services\FootballService;

class FootballController extends Controller
{
    public function schedule(FootballService $footballService)
    {
        $teamId = 83; // your team id
        $from = now()->format('Y-m-d'); // today
        $to = now()->addMonths(6)->format('Y-m-d'); // next 6 months

        $matches = $footballService->getTeamScheduleBetween($teamId, $from, $to);

        return view('football.schedule', compact('matches'));
    }
}
