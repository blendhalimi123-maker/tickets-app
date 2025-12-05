@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center py-5" style="min-height: 100vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 650px; border-radius: 1rem;">
        <h2 class="text-center mb-4 fw-bold">Team Schedule</h2>

        @if(count($matches))
            <ul class="list-group list-group-flush">
                @foreach($matches as $match)
                    <li class="list-group-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ $match['participants'][0]['image_path'] ?? '' }}" class="me-2" width="40" height="40">
                                <span>{{ $match['participants'][0]['name'] ?? 'Home' }}</span>
                            </div>

                            <strong>vs</strong>

                            <div class="d-flex align-items-center">
                                <span>{{ $match['participants'][1]['name'] ?? 'Away' }}</span>
                                <img src="{{ $match['participants'][1]['image_path'] ?? '' }}" class="ms-2" width="40" height="40">
                            </div>
                        </div>

                        <div class="mt-2 text-muted small">
                            {{ $match['starting_at'] ?? 'TBD' }} • 
                            {{ $match['venue']['name'] ?? 'Unknown Venue' }} • 
                            {{ $match['league']['name'] ?? '' }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-center text-muted mb-0">No matches found.</p>
        @endif

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="btn btn-success btn-lg">Back</a>
        </div>
    </div>
</div>
@endsection
