@extends('layouts.app')

@section('content')
    <h1>Edit Ticket</h1>

    @if ($errors->any())
        <div style="color:red;" a>
            <ui>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ui>
        </div>
    @endif


    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Title:</label><br>
        <input type="text" name="title" value="{{ $ticket->title }}"><br><br>

        <label>Game Date:</label><br>
        <input type="datetime-local" name="game_date"
            value="{{ date('Y-m-d\TH:i', strtotime($ticket->game_date)) }}"><br><br>

        <label>Stadium:</label><br>
        <input type="text" name="stadium" value="{{ $ticket->stadium }}"><br><br>

        <label>Seat Info:</label><br>
        <input type="text" name="seat_info" value="{{ $ticket->seat_info }}"><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="{{ $ticket->price }}"><br><br>

        <label>Available:</label><br>
        <select name="is_available">
            <option value="1" {{ $ticket->is_available ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$ticket->is_available ? 'selected' : '' }}>No</option>
        </select><br><br>

        <button type="submit">Update Ticket</button>
    </form>
@endsection