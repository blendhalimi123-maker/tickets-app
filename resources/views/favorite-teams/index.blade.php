@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Your Favorite Teams — Next Matches</h1>

    @if(empty($teamInfos) || count($teamInfos) === 0)
        <p>You have no favorite teams yet.</p>
    @else
        <div class="row">
            @foreach($teamInfos as $info)
                @php
                    $team = $info['team'];
                    $next = $info['nextMatch'] ?? null;
                    $teamInfo = $info['teamInfo'] ?? [];
                    $website = $info['website'] ?? ($teamInfo['website'] ?? null);
                @endphp
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if($team->crest)
                                    <img src="{{ $team->crest }}" alt="crest" style="width:48px;height:48px;margin-right:12px;">
                                @endif
                                <h5 class="card-title mb-0">{{ $team->name }}</h5>
                            </div>

                            <hr>

                            @if($next)
                                <p><strong>Next match:</strong></p>
                                <p>
                                    @php
                                        $home = $next['homeTeam']['name'] ?? $next['home_team']['name'] ?? $next['homeTeam'] ?? null;
                                        $away = $next['awayTeam']['name'] ?? $next['away_team']['name'] ?? $next['awayTeam'] ?? null;
                                        $date = $next['utcDate'] ?? $next['date'] ?? $next['scheduled'] ?? null;
                                    @endphp
                                    @if($home && $away)
                                        {{ $home }} vs {{ $away }}<br>
                                    @elseif(!empty($next['opponent']))
                                        Versus {{ $next['opponent'] }}<br>
                                    @endif
                                    <small class="text-muted">{{ $date ? \Carbon\Carbon::parse($date)->toDayDateTimeString() : '' }}</small>
                                </p>
                            @else
                                <p class="text-muted">No upcoming match found.</p>
                            @endif

                            <div class="d-flex gap-2">
                                @if($website)
                                    <a href="{{ $website }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">Visit Official Website</a>
                                @endif

                                <form method="POST" action="{{ route('favorite-teams.toggle', ['teamId' => $team->api_team_id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Unfavorite</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <a href="{{ route('user.dashboard') }}" class="btn btn-link mt-3">Back to dashboard</a>
</div>
@endsection
