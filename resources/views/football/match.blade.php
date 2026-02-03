@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @if(!$match)
            <h1 class="h4 fw-bold mb-2">Match</h1>
            <div class="alert alert-warning border mb-0">Match not found.</div>
            <a href="javascript:history.back()" class="btn btn-link mt-3 px-0">Back</a>
        @else
            @if($source === 'sportmonks')
                @php
                    $participants = collect($match['participants'] ?? []);
                    $home = $participants->first(fn ($p) => ($p['meta']['location'] ?? null) === 'home') ?: $participants->get(0);
                    $away = $participants->first(fn ($p) => ($p['meta']['location'] ?? null) === 'away') ?: $participants->get(1);

                    $homeName = $home['name'] ?? 'Home';
                    $awayName = $away['name'] ?? 'Away';
                    $result = $match['result_info'] ?? null;
                    $league = $match['league']['name'] ?? null;
                    $venue = $match['venue']['name'] ?? null;
                    $state = $match['state']['name'] ?? ($match['state']['state'] ?? ($match['state'] ?? null));
                    $kickoff = $match['starting_at'] ?? null;
                @endphp

                <h1 class="h4 fw-bold mb-1">{{ $homeName }} vs {{ $awayName }}</h1>
                <div class="text-muted small mb-3">
                    @if($league) {{ $league }} @endif
                    @if($league && $venue) • @endif
                    @if($venue) {{ $venue }} @endif
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="fw-semibold">{{ $homeName }}</div>
                            <div class="fs-3 fw-bold">{{ $result ?? '-' }}</div>
                            <div class="fw-semibold">{{ $awayName }}</div>
                        </div>

                        <div class="text-muted small mt-2">
                            @if($state) {{ $state }} @endif
                            @if($state && $kickoff) • @endif
                            @if($kickoff) Kickoff: {{ \Carbon\Carbon::parse($kickoff)->toDayDateTimeString() }} @endif
                        </div>
                    </div>
                </div>
            @else
                @php
                    $home = $match['homeTeam']['name'] ?? ($match['homeTeam']['shortName'] ?? 'Home');
                    $away = $match['awayTeam']['name'] ?? ($match['awayTeam']['shortName'] ?? 'Away');
                    $score = $match['score']['fullTime'] ?? ($match['score']['regularTime'] ?? []);
                    $homeScore = $score['home'] ?? null;
                    $awayScore = $score['away'] ?? null;
                    $result = (is_numeric($homeScore) && is_numeric($awayScore)) ? ($homeScore . ' - ' . $awayScore) : '-';
                    $status = $match['status'] ?? null;
                    $utc = $match['utcDate'] ?? null;
                    $competition = $match['competition']['name'] ?? null;
                @endphp

                <h1 class="h4 fw-bold mb-1">{{ $home }} vs {{ $away }}</h1>
                <div class="text-muted small mb-3">
                    @if($competition) {{ $competition }} @endif
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="fw-semibold">{{ $home }}</div>
                            <div class="fs-3 fw-bold">{{ $result }}</div>
                            <div class="fw-semibold">{{ $away }}</div>
                        </div>

                        <div class="text-muted small mt-2">
                            @if($status) {{ ucfirst(strtolower($status)) }} @endif
                            @if($status && $utc) • @endif
                            @if($utc) Kickoff: {{ \Carbon\Carbon::parse($utc)->toDayDateTimeString() }} @endif
                        </div>
                    </div>
                </div>
            @endif

            <a href="javascript:history.back()" class="btn btn-link mt-3 px-0">Back</a>
        @endif
    </div>
@endsection
