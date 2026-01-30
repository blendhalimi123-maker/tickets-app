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
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-2" style="background-color: var(--primary-color);">
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
                                <div class="text-muted small">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID: {{ $game->api_game_id }}
                                </div>
                                <form action="{{ route('favorites.toggle', $game->api_game_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-warning">
                                        <i class="fas fa-star fa-lg"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="text-center my-3">
                                <h5 class="fw-bold">{{ $game->title }}</h5>
                                <p class="text-muted small">Premier League Match</p>
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
@endsection