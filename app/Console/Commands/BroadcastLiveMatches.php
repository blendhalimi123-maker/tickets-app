<?php

namespace App\Console\Commands;

use App\Events\LiveMatchesUpdated;
use App\Services\FootballService;
use Illuminate\Console\Command;

class BroadcastLiveMatches extends Command
{
    protected $signature = 'football:broadcast-live-matches {--cache-minutes=0.25 : Cache minutes for SportMonks inplay fetch}';
    protected $description = 'Broadcast live matches snapshot for UI live detection';

    public function __construct(private readonly FootballService $footballService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $cacheMinutes = (float) $this->option('cache-minutes');
        $data = $this->footballService->getSportMonksInplay($cacheMinutes);

        $fixtures = is_array($data['data'] ?? null) ? $data['data'] : [];
        $matches = $this->normalizeFixtures($fixtures);

        event(new LiveMatchesUpdated($matches));
        $this->info('Broadcasted ' . count($matches) . ' live match(es).');

        return self::SUCCESS;
    }

    /**
     * @param array<int, array<string, mixed>> $fixtures
     * @return array<int, array<string, mixed>>
     */
    private function normalizeFixtures(array $fixtures): array
    {
        $out = [];

        foreach ($fixtures as $fixture) {
            $fixtureId = $fixture['id'] ?? null;
            if (!$fixtureId) {
                continue;
            }

            $participants = [];
            if (!empty($fixture['participants']) && is_array($fixture['participants'])) {
                foreach ($fixture['participants'] as $p) {
                    if (!is_array($p)) {
                        continue;
                    }
                    $participants[] = [
                        'id' => $p['id'] ?? null,
                        'name' => $p['name'] ?? null,
                        'short_code' => $p['short_code'] ?? ($p['short_name'] ?? null),
                        'meta' => isset($p['meta']) && is_array($p['meta']) ? $p['meta'] : null,
                    ];
                }
            }

            $state = null;
            if (isset($fixture['state']) && is_array($fixture['state'])) {
                $state = $fixture['state']['name'] ?? ($fixture['state']['state'] ?? null);
            }

            $out[] = [
                'id' => $fixtureId,
                'state' => $state,
                'starting_at' => $fixture['starting_at'] ?? null,
                'result_info' => $fixture['result_info'] ?? null,
                'participants' => $participants,
            ];
        }

        return $out;
    }
}
