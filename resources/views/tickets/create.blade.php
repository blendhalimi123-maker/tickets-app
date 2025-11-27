@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-lg rounded-4 w-100" style="max-width: 600px;">

        <!-- Header -->
        <div class="text-center text-white py-4" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
            <h1 class="fw-bold display-5 mb-2">Create New Ticket</h1>
            <p class="mb-0 fs-6">Fill in the details below to add a new match ticket</p>
        </div>

        <!-- Body -->
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tickets.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g., Kosovo V Albania" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Game Date</label>
                    <input type="datetime-local" name="game_date" class="form-control" value="{{ old('game_date') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Stadium</label>
                    <input type="text" name="stadium" class="form-control" value="{{ old('stadium') }}" placeholder="e.g., Fadil Vokrri" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Seat Info</label>
                    <input type="text" name="seat_info" class="form-control" value="{{ old('seat_info') }}" placeholder="e.g., P3, B5, C21" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" placeholder="e.g., 5" required>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="is_available" name="is_available" {{ old('is_available', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is_available">
                        Available
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    Create Ticket
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
