<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FootballService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FootballController extends Controller
{
    protected $footballService;
    
    public function __construct(FootballService $footballService)
    {
        $this->footballService = $footballService;
    }

    private function paginateCollection($items, $perPage = 10)
    {
        $page = request()->get('page', 1);
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($items, $offset, $perPage, true),
            count($items),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
    
    public function championsLeague()
    {
        try {
            $data = $this->footballService->getChampionsLeagueMatches();
            $matches = $data['matches'] ?? [];
            
            return response()->json([
                'success' => true,
                'matches' => $matches
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
            $data = $this->footballService->getPremierLeagueMatches();
            
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
            $data = $this->footballService->getWorldCupMatches();
            $matches = $data['matches'] ?? [];

            return response()->json([
                'success' => true,
                'matches' => $matches
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
            $matches = $data['matches'] ?? [];
            
            return response()->json([
                'success' => true,
                'matches' => $matches
            ]);
        } catch (\Exception $e) {
            \Log::error('All Competitions API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data',
                'matches' => []
            ], 500);
        }
    }
}