@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow rounded-lg mx-auto" style="max-width: 700px;">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Edit Ticket</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Match (Title)</label>
                    <input type="text" name="title" class="form-control" value="{{ $ticket->title }}" required>
                </div>

                <!-- Game Date -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Date & Time</label>
                    <input type="datetime-local" name="game_date" class="form-control"
                        value="{{ date('Y-m-d\TH:i', strtotime($ticket->game_date)) }}" required>
                </div>

                <!-- Stadium -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Stadium</label>
                    <input type="text" name="stadium" class="form-control" value="{{ $ticket->stadium }}" required>
                </div>

                <!-- Seat Info -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Seat Info</label>
                    <input type="text" name="seat_info" class="form-control" value="{{ $ticket->seat_info }}" required>
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $ticket->price }}" required>
                </div>

                <!-- Availability -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Available</label>
                    <select name="is_available" class="form-select">
                        <option value="1" {{ $ticket->is_available ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$ticket->is_available ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
