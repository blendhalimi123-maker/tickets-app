<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FootballService
{
    private $apiKey = '76fa58e62936408ca8d3dbb65e50c517';
    private $baseUrl = 'https://api.football-data.org/v4';
    
    public function getCompetitionMatches($competitionCode, $cacheMinutes = 10)
    {
        return Cache::remember("football_matches_{$competitionCode}", $cacheMinutes * 60, function () use ($competitionCode) {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['X-Auth-Token' => $this->apiKey])
                ->get("{$this->baseUrl}/competitions/{$competitionCode}/matches", [
                    'status' => 'SCHEDULED'
                ]);

            if ($response->successful()) {
                $json = $response->json();
                return ['matches' => $json['matches'] ?? []];
            }

            \Log::error("Football API Error ({$competitionCode}): " . $response->status());
            return ['matches' => []];
        });
    }

    public function getChampionsLeagueMatches($cacheMinutes = 10)
    {
        return $this->getCompetitionMatches('CL', $cacheMinutes);
    }

    public function getPremierLeagueMatches($cacheMinutes = 10)
    {
        return $this->getCompetitionMatches('PL', $cacheMinutes);
    }

    public function getWorldCupMatches($cacheMinutes = 10)
    {
        return $this->getCompetitionMatches('WC', $cacheMinutes);
    }

    public function getMatchById($matchId)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders(['X-Auth-Token' => $this->apiKey])
            ->get("{$this->baseUrl}/matches/{$matchId}");

        if (!$response->successful()) {
            \Log::error("Football API match lookup error ({$matchId}): " . $response->status());
            return null;
        }

        $data = $response->json();
        return $data['match'] ?? null;
    }

    public function getAllCompetitions()
    {
        $competitions = [
            'CL' => 'UEFA Champions League',
            'PL' => 'Premier League',
            'WC' => 'FIFA World Cup'
        ];

        $data = [];

        foreach ($competitions as $code => $name) {
            $matches = $this->getCompetitionMatches($code);
            $data[strtolower(str_replace(' ', '_', $name))] = [
                'name' => $name,
                'code' => $code,
                'matches' => $matches['matches'] ?? [],
                'count' => count($matches['matches'] ?? [])
            ];
        }

        return $data;
    }

    public function getTeamMatches($teamId)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders(['X-Auth-Token' => $this->apiKey])
            ->get("{$this->baseUrl}/teams/{$teamId}/matches", [
                'status' => 'SCHEDULED'
            ]);

        return $response->successful() ? ['matches' => $response->json()['matches'] ?? []] : ['matches' => []];
    }

    /**
     * Fetch in-play/live events from SportMonks (alternative provider).
     * Uses SPORTMONKS_API_TOKEN from env and caches results.
     */
    public function getSportMonksInplay($cacheMinutes = 1)
    {
        $token = env('SPORTMONKS_API_TOKEN');
        if (!$token) {
            \Log::warning('SPORTMONKS_API_TOKEN not configured');
            return ['data' => []];
        }

        return Cache::remember('sportmonks_inplay', $cacheMinutes * 60, function () use ($token) {
            $base = 'https://api.sportmonks.com/v3/football/livescores/inplay';
            $response = Http::withOptions(['verify' => false])
                ->get($base, [
                    'api_token' => $token,
                    'include' => 'participants,scores,periods,events,venue,league'
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error('SportMonks API error: ' . $response->status());
            return ['data' => []];
        });
    }
}
