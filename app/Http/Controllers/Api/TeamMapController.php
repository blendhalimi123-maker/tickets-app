<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamMapController extends Controller
{
    public function map(Request $request)
    {
        $names = $request->query('names', '');
        if (!$names) {
            return response()->json(['mapping' => []]);
        }

        $list = array_map('trim', explode(',', $names));
        $teams = Team::whereIn('name', $list)->get();
        $mapping = [];
        foreach ($teams as $t) {
            $mapping[$t->name] = $t->api_team_id;
        }

        return response()->json(['mapping' => $mapping]);
    }
}
