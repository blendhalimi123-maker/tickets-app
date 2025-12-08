<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FootballService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = 'https://api.sportmonks.com/v3/football';
        $this->token = env('SPORTMONKS_API_TOKEN');
    }

    public function getTeamScheduleBetween($teamId, $from, $to)
    {
        $response = Http::withToken($this->token)
            ->withOptions(['verify' => false])
            ->get("{$this->baseUrl}/fixtures/between/{$from}/{$to}/{$teamId}?include=participants;league;venue;scores")
            ->json();

        return $response['data'] ?? [];
    }
}
