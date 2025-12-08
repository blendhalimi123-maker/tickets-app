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
            // ADD THIS LINE TO DISABLE SSL VERIFICATION
            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'X-Auth-Token' => $this->apiKey
            ])->get("{$this->baseUrl}/competitions/{$competitionCode}/matches", [
                'status' => 'SCHEDULED',
                'limit' => 50
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            \Log::error('Football API Error: ' . $response->status());
            return ['matches' => []];
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
    
    public function getTeamMatches($teamId)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'X-Auth-Token' => $this->apiKey
        ])->get("{$this->baseUrl}/teams/{$teamId}/matches", [
            'status' => 'SCHEDULED',
            'limit' => 20
        ]);
        
        return $response->successful() ? $response->json() : ['matches' => []];
    }
}