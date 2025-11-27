@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-10">
    <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-300">

        <!-- Left perforated lines -->
        <div class="absolute -left-2 top-2 bottom-2 flex flex-col justify-between space-y-2">
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
        </div>

        <!-- Right perforated lines -->
        <div class="absolute -right-2 top-2 bottom-2 flex flex-col justify-between space-y-2">
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
            <span class="block w-1 h-4 bg-gray-300 rounded-full"></span>
        </div>

        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-4">
            <h2 class="text-2xl font-bold">{{ $ticket->title }}</h2>
            <p class="text-sm mt-1">Event Ticket</p>
        </div>

        <!-- Ticket body -->
        <div class="px-6 py-4 divide-y divide-gray-200">
            <div class="flex justify-between py-2">
                <span class="font-semibold text-gray-700">Date:</span>
                <span class="text-gray-800">{{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}</span>
            </div>
            <div class="flex justify-between py-2 bg-gray-50 px-2 rounded-md my-1">
                <span class="font-semibold text-gray-700">Stadium:</span>
                <span class="text-gray-800">{{ $ticket->stadium }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="font-semibold text-gray-700">Seat:</span>
                <span class="text-gray-800">{{ $ticket->seat_info }}</span>
            </div>
            <div class="flex justify-between py-2 bg-gray-50 px-2 rounded-md my-1">
                <span class="font-semibold text-gray-700">Price:</span>
                <span class="text-gray-800">${{ $ticket->price }}</span>
            </div>
        </div>

        <!-- Status badge -->
        <div class="absolute top-4 right-4">
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $ticket->is_available ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ $ticket->is_available ? 'Available' : 'Sold' }}
            </span>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-200">
            <p class="text-gray-400 text-sm">Scan at entrance</p>
        </div>
    </div>
</div>
@endsection

