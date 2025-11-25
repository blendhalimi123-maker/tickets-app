@extends('layouts.app')


    @section('content')
        <h1>Create Ticket</h1>

        @if ($errors->any())
            <div style="color:red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <label>Title:</label><br>
            <input type="text" name="title" value="{{ old('title') }}"><br><br>

            <label>Game Date:</label><br>
            <input type="datetime-local" name="game_date" value="{{ old('game_date') }}"><br><br>

            <label>Stadium:</label><br>
            <input type="text" name="stadium" value="{{ old('stadium') }}"><br><br>

            <label>Seat Info:</label><br>
            <input type="text" name="seat_info" value="{{ old('seat_info') }}"><br><br>

            <label>Price:</label><br>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}"><br><br>

            <button type="submit">Create Ticket</button>
        </form>
    @endsection



















