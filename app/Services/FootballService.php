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

    public function getCompetitionStandings($competitionCode, $cacheMinutes = 10)
    {
        return Cache::remember("football_standings_{$competitionCode}", $cacheMinutes * 60, function () use ($competitionCode) {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['X-Auth-Token' => $this->apiKey])
                ->get("{$this->baseUrl}/competitions/{$competitionCode}/standings");

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error("Football standings API Error ({$competitionCode}): " . $response->status());
            return ['standings' => []];
        });
    }

        public function getSportMonksStandings($roundId, $cacheMinutes = 5)
        {
            $token = env('SPORTMONKS_API_TOKEN');
            if (!$token) {
                \Log::warning('SPORTMONKS_API_TOKEN not configured');
                return ['data' => []];
            }

            return Cache::remember("sportmonks_standings_{$roundId}", $cacheMinutes * 60, function () use ($token, $roundId) {
                $base = "https://api.sportmonks.com/v3/football/standings/rounds/{$roundId}";
                $response = Http::withOptions(['verify' => false])
                    ->get($base, [
                        'api_token' => $token,
                        'include' => 'stage;league;details.type;participant'
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                \Log::error('SportMonks standings API error: ' . $response->status());
                return ['data' => []];
            });
        }

    public function getTeamInfo($teamId, $cacheMinutes = 60)
    {
        return Cache::remember("football_team_{$teamId}", $cacheMinutes * 60, function () use ($teamId) {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['X-Auth-Token' => $this->apiKey])
                ->get("{$this->baseUrl}/teams/{$teamId}");

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error("Football Team API Error ({$teamId}): " . $response->status());
            return null;
        });
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

    
    public function getSportMonksInplay($cacheMinutes = 1)
    {
        $token = env('SPORTMONKS_API_TOKEN_OLD') ?: env('SPORTMONKS_API_TOKEN');
        if (!$token) {
            \Log::warning('SPORTMONKS API token(s) not configured');
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

    public function getTeamMatches($teamId)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders(['X-Auth-Token' => $this->apiKey])
            ->get("{$this->baseUrl}/teams/{$teamId}/matches", [
                'status' => 'SCHEDULED'
            ]);

        return $response->successful() ? ['matches' => $response->json()['matches'] ?? []] : ['matches' => []];
    }
}
