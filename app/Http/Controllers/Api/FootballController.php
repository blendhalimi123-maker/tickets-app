<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FootballService;

class FootballController extends Controller
{
    protected $footballService;
    
    public function __construct(FootballService $footballService)
    {
        $this->footballService = $footballService;
    }
    
    public function championsLeague()
    {
        try {
            $data = $this->footballService->getCompetitionMatches('CL');
            return response()->json([
                'success' => true,
                'matches' => $data['matches'] ?? []
            ]);
        } catch (\Exception $e) {
            \Log::error('Champions League API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data',
                'matches' => []
            ], 500);
        }
    }
    
    public function premierLeague()
    {
        try {
            $data = $this->footballService->getCompetitionMatches('PL');
            return response()->json([
                'success' => true,
                'matches' => $data['matches'] ?? []
            ]);
        } catch (\Exception $e) {
            \Log::error('Premier League API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data',
                'matches' => []
            ], 500);
        }
    }
    
    public function worldCup()
    {
        try {
            $data = $this->footballService->getCompetitionMatches('WC');
            return response()->json([
                'success' => true,
                'matches' => $data['matches'] ?? []
            ]);
        } catch (\Exception $e) {
            \Log::error('World Cup API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data',
                'matches' => []
            ], 500);
        }
    }
    
    public function allCompetitions()
    {
        try {
            $data = $this->footballService->getAllCompetitions();
            return response()->json([
                'success' => true,
                'competitions' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('All Competitions API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data',
                'competitions' => []
            ], 500);
        }
    }
}