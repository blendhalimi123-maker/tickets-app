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
            return response()->json(['favorites' => []]);
        }

        $favorites = $user->favoriteTeams()->pluck('api_team_id');
        return response()->json(['favorites' => $favorites]);
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

            $teamInfo = $this->footballService->getTeamInfo($apiTeamId);

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

