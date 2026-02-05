<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SquadController extends Controller
{
    public function show($id)
    {
        $token = env('SPORTMONKS_API_TOKEN');

        $url = "https://api.sportmonks.com/v3/football/squads/teams/{$id}";

        $response = Http::acceptJson()->get($url, [
            'api_token' => $token,
            'include' => 'player',
        ]);

        if ($response->failed()) {
            $data = collect();
            return view('squad', ['squad' => $data, 'teamId' => $id, 'error' => $response->body()]);
        }

        $payload = $response->json();

        $correctId = null;
        if (!empty($payload['data']) && is_array($payload['data'])) {
            $first = $payload['data'][0] ?? null;
            if ($first) {
                if (isset($first['team_id'])) {
                    $correctId = $first['team_id'];
                } elseif (isset($first['team']) && is_array($first['team'])) {
                    if (isset($first['team']['data']['id'])) {
                        $correctId = $first['team']['data']['id'];
                    } elseif (isset($first['team']['id'])) {
                        $correctId = $first['team']['id'];
                    }
                }
            }
        }

        if (!$correctId && !empty($payload['included']) && is_array($payload['included'])) {
            foreach ($payload['included'] as $inc) {
                if (($inc['type'] ?? '') === 'team' && isset($inc['id'])) {
                    $correctId = $inc['id'];
                    break;
                }
            }
        }

        if ($correctId && (string) $correctId !== (string) $id) {
            return redirect()->route('squad.show', ['id' => $correctId]);
        }

        

        $entries = $payload['data'] ?? [];
        $included = $payload['included'] ?? [];

        $includedPlayers = [];
        foreach ($included as $inc) {
            $incId = $inc['id'] ?? null;
            $incType = $inc['type'] ?? null;
            if (!$incId) continue;
            if ($incType === 'player' || ($inc['attributes'] ?? null)) {
                $includedPlayers[$incId] = $inc['attributes'] ?? $inc;
            }
        }

        $squad = collect($entries)->map(function ($item) use ($includedPlayers) {
            $playerData = null;

            if (isset($item['player']) && is_array($item['player'])) {
                if (isset($item['player']['data']) && is_array($item['player']['data']) && isset($item['player']['data']['id'])) {
                    $pid = $item['player']['data']['id'];
                    if (isset($includedPlayers[$pid])) {
                        $playerData = $includedPlayers[$pid];
                    } else {
                        $playerData = $item['player']['data'];
                    }
                } elseif (isset($item['player']['id'])) {
                    $pid = $item['player']['id'];
                    if (isset($includedPlayers[$pid])) {
                        $playerData = $includedPlayers[$pid];
                    } else {
                        $playerData = $item['player'];
                    }
                } else {
                    $playerData = $item['player'];
                }
            }

            if (!$playerData && isset($item['player_id'])) {
                $pid = $item['player_id'];
                if (isset($includedPlayers[$pid])) {
                    $playerData = $includedPlayers[$pid];
                }
            }

            $name = null;
            if (is_array($playerData)) {
                $name = $playerData['name'] ?? $playerData['display_name'] ?? $playerData['common_name'] ?? $playerData['full_name'] ?? $playerData['fullName'] ?? null;
            }

            if (!$name && isset($item['name'])) {
                $name = $item['name'];
            }

            $number = $item['jersey_number'] ?? $item['number'] ?? ($playerData['jersey_number'] ?? $playerData['number'] ?? null);

            $posId = $item['position_id'] ?? ($playerData['position_id'] ?? ($item['detailed_position_id'] ?? ($playerData['detailed_position_id'] ?? null)));
            $positionMap = [
                24 => 'Goalkeeper',
                25 => 'Defender',
                26 => 'Midfielder',
                27 => 'Attacker',
            ];
            $position = $positionMap[$posId] ?? ($playerData['position'] ?? ($playerData['position_name'] ?? null));

            $image = null;
            if (is_array($playerData)) {
                $image = $playerData['image_path'] ?? $playerData['image'] ?? null;
            }

            return [
                'name' => $name,
                'number' => $number,
                'position' => $position,
                'image' => $image,
            ];
        });

        $squad = $squad->sortBy(function ($m) {
            return $m['number'] === null ? 9999 : (int) $m['number'];
        })->values();

        return view('squad', ['squad' => $squad, 'teamId' => $id]);
    }
}
