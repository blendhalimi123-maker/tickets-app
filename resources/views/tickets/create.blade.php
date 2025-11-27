@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-10 bg-white shadow-lg rounded-lg p-6">

    <h1 class="text-2xl font-bold mb-6 text-gray-800">Create New Ticket</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-2 font-semibold text-gray-700">Title</label>
            <input type="text" name="title" 
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-600" 
                   value="{{ old('title') }}">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold text-gray-700">Game Date</label>
            <input type="datetime-local" name="game_date" 
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                   value="{{ old('game_date') }}">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold text-gray-700">Stadium</label>
            <input type="text" name="stadium" 
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                   value="{{ old('stadium') }}">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold text-gray-700">Seat Info</label>
            <input type="text" name="seat_info" 
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                   value="{{ old('seat_info') }}">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold text-gray-700">Price</label>
            <input type="number" name="price" step="0.01" 
                   class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                   value="{{ old('price') }}">
        </div>

        <div class="mb-4 flex items-center space-x-2">
            <input type="checkbox" name="is_available" id="is_available" 
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                   {{ old('is_available', true) ? 'checked' : '' }}>
            <label for="is_available" class="font-semibold text-gray-700">Available</label>
        </div>

        <button type="submit" 
                class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Create Ticket
        </button>
    </form>
</div>
@endsection














