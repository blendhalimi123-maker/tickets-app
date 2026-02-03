<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\FootballService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteTeamController extends Controller
{
    protected $footballService;

    public function __construct(FootballService $footballService)
    {
        $this->footballService = $footballService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['favorites' => []]);
            }

            return view('favorite-teams.index', ['favorites' => [], 'teams' => [], 'teamInfos' => []]);
        }

        $favorites = $user->favoriteTeams()->pluck('api_team_id');

        if ($request->wantsJson()) {
            return response()->json(['favorites' => $favorites]);
        }

        $teams = \App\Models\Team::whereIn('api_team_id', $favorites)->get();
        $teamInfos = [];
        foreach ($teams as $team) {
            $nextMatch = $this->footballService->getTeamNextMatch($team->api_team_id);
            $teamInfo = $this->footballService->getTeamInfo($team->api_team_id) ?: [];

            $website = null;
            if (!empty($teamInfo['website'])) {
                $website = $teamInfo['website'];
            } elseif (!empty($teamInfo['address']) && filter_var($teamInfo['address'], FILTER_VALIDATE_URL)) {
                $website = $teamInfo['address'];
            }

            $teamInfos[] = [
                'team' => $team,
                'nextMatch' => $nextMatch,
                'teamInfo' => $teamInfo,
                'website' => $website,
            ];
        }

        return view('favorite-teams.index', [
            'favorites' => $favorites,
            'teams' => $teams,
            'teamInfos' => $teamInfos,
        ]);
    }

    public function toggle(Request $request, $apiTeamId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $name = $request->input('name') ?: ('Team ' . $apiTeamId);
            $crest = $request->input('crest') ?: null;

            $team = Team::updateOrCreate(
                ['api_team_id' => $apiTeamId],
                ['name' => $name, 'crest' => $crest]
            );

            if ($user->hasFavoritedTeam($apiTeamId)) {
                $user->unfavoriteTeam($apiTeamId);
                return response()->json(['status' => 'unfavorited', 'id' => $apiTeamId]);
            }

            $user->favoriteTeam($apiTeamId);
            $team->refresh();

            $teamInfo = $this->footballService->getTeamInfo($apiTeamId) ?: [];

            $scorersData = $this->footballService->getCompetitionScorers('PD');
            $scorersList = $scorersData['scorers'] ?? ($scorersData['data'] ?? []);
            $topScorerNow = null;
            foreach ($scorersList as $s) {
                $teamObj = $s['team'] ?? $s['team'] ?? null;
                $teamIdField = $teamObj['id'] ?? ($teamObj['teamId'] ?? null);
                if ($teamIdField && (string)$teamIdField === (string)$apiTeamId) {
                    $topScorerNow = $s;
                    break;
                }
            }

            $nextMatch = $this->footballService->getTeamNextMatch($apiTeamId);

            $teamInfo['topScorerNow'] = $topScorerNow;
            $teamInfo['topScorerAllTime'] = null;
            $teamInfo['nextMatch'] = $nextMatch;

            return response()->json([
                'status' => 'favorited',
                'team' => [
                    'id' => $team->api_team_id,
                    'name' => $team->name,
                    'crest' => $team->crest,
                ],
                'teamInfo' => $teamInfo,
            ]);
        } catch (\Throwable $e) {
            \Log::error('FavoriteTeam toggle error: ' . $e->getMessage(), [
                'teamId' => $apiTeamId,
                'user' => Auth::id(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Server error while toggling favorite team', 'details' => $e->getMessage()], 500);
        }
    }
}

