@extends('layouts.app')

@section('content')
    @php
        $teamName = $teamInfo['name'] ?? $teamInfo['shortName'] ?? ('Team ' . $teamId);
        $crest = $teamInfo['crest'] ?? null;
    @endphp

    <div class="container py-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            @if($crest)
                <img src="{{ $crest }}" alt="crest" style="width: 42px; height: 42px; object-fit: contain;">
            @endif
            <div>
                <h1 class="h4 fw-bold mb-0">{{ $teamName }}</h1>
                <div class="text-muted small">Upcoming fixtures</div>
            </div>
        </div>

        @if(empty($matches))
            <div class="alert alert-light border mb-0">No upcoming fixtures found.</div>
        @else
            <div class="list-group shadow-sm">
                @foreach($matches as $m)
                    @php
                        $homeObj = $m['homeTeam'] ?? ($m['home_team'] ?? []);
                        $awayObj = $m['awayTeam'] ?? ($m['away_team'] ?? []);
                        $home = $homeObj['name'] ?? ($homeObj['shortName'] ?? ($homeObj['teamName'] ?? 'Home'));
                        $away = $awayObj['name'] ?? ($awayObj['shortName'] ?? ($awayObj['teamName'] ?? 'Away'));
                        $utc = $m['utcDate'] ?? ($m['match_date'] ?? ($m['date'] ?? null));
                        $when = $utc ? \Carbon\Carbon::parse($utc)->toDayDateTimeString() : null;
                        $competition = $m['competition']['name'] ?? ($m['league']['name'] ?? null);
                        $status = $m['status'] ?? null;
                    @endphp
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                            <div class="fw-semibold">{{ $home }} vs {{ $away }}</div>
                            @if($when)
                                <div class="text-muted small">{{ $when }}</div>
                            @endif
                        </div>
                        @if($competition || $status)
                            <div class="text-muted small">
                                {{ $competition }}
                                @if($competition && $status)
                                    •
                                @endif
                                @if($status)
                                    {{ ucfirst(strtolower($status)) }}
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <a href="javascript:history.back()" class="btn btn-link mt-3 px-0">Back</a>
    </div>
@endsection

