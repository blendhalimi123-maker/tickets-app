@extends('layouts.app')

@section('title', 'Team Squad')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-gradient rounded-4 p-4 d-flex align-items-center justify-content-between shadow-sm squad-hero">
                <div>
                    <h2 class="mb-1 fw-bold">Team Squad</h2>
                    <p class="mb-0 text-muted">Browse players, numbers and positions. Team ID: <span class="fw-semibold">{{ $teamId ?? '—' }}</span></p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="small text-muted">Players</div>
                        <div class="h5 mb-0">{{ $squad->count() }}</div>
                    </div>
                    <button class="btn btn-outline-light" onclick="history.back()"><i class="fas fa-arrow-left me-1"></i> Back</button>
                </div>
            </div>
        </div>

        <div class="col-12">
            @if(!empty($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            @if($squad->isEmpty())
                <div class="alert alert-info">No squad information available.</div>
            @else
                <div class="mb-3 d-flex justify-content-end">
                    <input id="squadSearch" class="form-control form-control-sm" placeholder="Search players..." style="max-width: 320px;">
                </div>

                @php
                    $groups = $squad->groupBy(function ($m) { return $m['position'] ?? 'Other'; });
                    $positionOrder = ['Goalkeeper', 'Defender', 'Midfielder', 'Attacker'];
                    $positionLabels = ['Goalkeeper' => 'Keepers', 'Defender' => 'Defenders', 'Midfielder' => 'Midfielders', 'Attacker' => 'Attackers'];
                @endphp

                @foreach($positionOrder as $pos)
                    @php $group = $groups->get($pos) ?? collect(); @endphp
                    @if($group->isNotEmpty())
                        <h5 class="mt-4 mb-3 text-uppercase text-muted small">{{ $positionLabels[$pos] ?? $pos }}</h5>
                        <div class="row g-3" id="group-{{ Illuminate\Support\Str::slug($pos) }}">
                            @foreach($group as $member)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="card player-card h-100 border-0 shadow-sm">
                                        <div class="card-body d-flex flex-column align-items-center text-center p-3">
                                            @if(!empty($member['image']))
                                                <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="player-avatar mb-2 rounded-circle" onerror="this.style.display='none'">
                                            @else
                                                <div class="player-avatar mb-2 rounded-circle bg-light d-flex align-items-center justify-content-center text-muted">—</div>
                                            @endif

                                            <div class="fw-bold">{{ $member['name'] ?? '—' }}</div>
                                            <div class="small text-muted mb-2">{{ $member['position'] ?? '—' }}</div>

                                            <div class="d-flex gap-2 mt-auto align-items-center w-100 justify-content-center">
                                                @if(!empty($member['number']))
                                                    <span class="badge bg-primary px-3 py-2">#{{ $member['number'] }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @foreach($groups->except($positionOrder) as $pos => $group)
                    @if($group->isNotEmpty())
                        <h5 class="mt-4 mb-3 text-uppercase text-muted small">{{ $pos ?: 'Other' }}</h5>
                        <div class="row g-3">
                            @foreach($group as $member)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="card player-card h-100 border-0 shadow-sm">
                                        <div class="card-body d-flex flex-column align-items-center text-center p-3">
                                            @if(!empty($member['image']))
                                                <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="player-avatar mb-2 rounded-circle" onerror="this.style.display='none'">
                                            @else
                                                <div class="player-avatar mb-2 rounded-circle bg-light d-flex align-items-center justify-content-center text-muted">—</div>
                                            @endif

                                            <div class="fw-bold">{{ $member['name'] ?? '—' }}</div>
                                            <div class="small text-muted mb-2">{{ $member['position'] ?? '—' }}</div>

                                            <div class="d-flex gap-2 mt-auto align-items-center w-100 justify-content-center">
                                                @if(!empty($member['number']))
                                                    <span class="badge bg-primary px-3 py-2">#{{ $member['number'] }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .squad-hero {
        background: linear-gradient(135deg, rgba(32,124,229,0.12), rgba(88,24,189,0.08));
    }
    .player-card { transition: transform .18s ease, box-shadow .18s ease; }
    .player-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(15,23,42,0.12); }
    .player-avatar { width:72px; height:72px; object-fit:cover; border:4px solid rgba(255,255,255,0.6); box-shadow: 0 6px 18px rgba(2,6,23,0.06); }
    @media (prefers-color-scheme: dark) {
        .player-avatar { border-color: rgba(255,255,255,0.06); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const search = document.getElementById('squadSearch');
    if (!search) return;
    search.addEventListener('input', function(){
        const q = this.value.trim().toLowerCase();
        document.querySelectorAll('.player-card').forEach(card => {
            const name = (card.querySelector('.fw-bold')?.textContent || '').toLowerCase();
            const col = card.closest('[class*="col-"]');
            if (!col) return;
            col.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    });
});
</script>
@endpush

@endsection
