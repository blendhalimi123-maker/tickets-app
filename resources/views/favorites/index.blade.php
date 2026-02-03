@extends('layouts.app')

@section('title', 'My Favorite Games')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: var(--primary-color);">My Favorite Games</h2>
            <span class="badge bg-secondary">{{ $favorites->count() }} Saved Matches</span>
        </div>

        @if($favorites->isEmpty())
            <div class="card shadow-sm border-0 text-center p-5">
                <div class="card-body">
                    <i class="far fa-star fa-3x mb-3 text-muted"></i>
                    <h4>No favorites yet</h4>
                    <p class="text-muted">Games you mark with a star will appear here for quick access.</p>
                    <a href="{{ route('football.schedule') }}" class="btn btn-primary mt-2"
                        style="background-color: var(--primary-color);">
                        Browse Matches
                    </a>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($favorites as $game)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 position-relative">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    @php
                                        $headerHome = $game->home_team ?? (strpos($game->title, ' vs ') !== false ? explode(' vs ', $game->title)[0] : null);
                                        $headerAway = $game->away_team ?? (strpos($game->title, ' vs ') !== false ? (explode(' vs ', $game->title)[1] ?? null) : null);
                                        $headerTitle = $headerHome || $headerAway ? trim(($headerHome ?? '') . ' vs ' . ($headerAway ?? '')) : null;
                                    @endphp
                                    <div class="text-muted small text-truncate" style="max-width:60%">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        @if($headerTitle)
                                            {{ $headerTitle }}
                                        @else
                                            ID: {{ $game->api_game_id }}
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-link p-0 text-warning favorite-toggle"
                                        data-game-id="{{ $game->api_game_id }}" aria-label="Remove Favorite">
                                        <i class="fas fa-star fa-lg"></i>
                                    </button>
                                </div>

                                <div class="text-center my-3">
                                    @php
                                        $homeDisplay = $game->home_team ?? (strpos($game->title, ' vs ') !== false ? explode(' vs ', $game->title)[0] : null);
                                        $awayDisplay = $game->away_team ?? (strpos($game->title, ' vs ') !== false ? (explode(' vs ', $game->title)[1] ?? null) : null);
                                    @endphp
                                    @if($homeDisplay || $awayDisplay)
                                        <h5 class="fw-bold">{{ trim(($homeDisplay ?? '') . ' vs ' . ($awayDisplay ?? '')) }}</h5>
                                    @else
                                        <h5 class="fw-bold">{{ $game->title ?? 'Match #' . $game->api_game_id }}</h5>
                                    @endif
                                    <!-- <p class="text-muted small">Premier League Match</p> -->
                                </div>

                                <hr class="text-light">

                                <div class="d-grid mt-3">
                                    <a href="{{ route('stadium.show', $game->id) }}" class="btn btn-sm btn-outline-primary">
                                        View Stadium & Tickets
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            (function () {
                const container = document.querySelector('.container');

                function updateSavedBadge(delta) {
                    const badge = document.getElementById('favorites-count');
                    if (!badge) return;
                    const match = badge.textContent.match(/(\d+)/);
                    let n = match ? parseInt(match[1], 10) : 0;
                    n = Math.max(0, n + (delta || 0));
                    badge.textContent = `${n} Saved Matches`;
                }

                container.addEventListener('click', async function (e) {
                    const btn = e.target.closest('.favorite-toggle');
                    if (!btn) return;
                    const apiId = btn.dataset.gameId;

                    if (!confirm('Remove this match from favorites?')) return;

                    try {
                        const res = await fetch(`/favorites/${apiId}`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (!res.ok) throw new Error('Network response was not ok');
                        const json = await res.json();
                        if (json.status === 'unfavorited') {
                            const cardCol = btn.closest('.col-md-6, .col-md-12, .col-lg-4');
                            if (cardCol) cardCol.remove();
                            updateSavedBadge(-1);
                        } else if (json.status === 'favorited') {
                            updateSavedBadge(1);
                        }
                    } catch (err) {
                        console.warn('Failed to toggle favorite from favorites page', err);
                        alert('Failed to remove favorite. Try again.');
                    }
                });

                window.addEventListener('favorite-updated', function (e) {
                    if (e.detail && e.detail.status === 'unfavorited') {
                        location.reload();
                    }
                    if (e.detail && e.detail.status === 'favorited') {
                        location.reload();
                    }
                });
            })();
        </script>
    @endpush

@endsection