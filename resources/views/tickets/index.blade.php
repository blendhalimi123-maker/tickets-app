@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">All Tickets</h1>

            <!-- Admin-only: Create Ticket -->
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('tickets.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Create New Ticket
                </a>
            @endif
        </div>

        <!-- Success message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Ticket cards -->
        <div class="space-y-6">
            @foreach($tickets as $ticket)
                <div
                    class="relative bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white rounded-xl shadow-lg overflow-hidden border-l-4 border-blue-700">

                    <!-- Decorative lines on left -->
                    <div class="absolute left-0 top-0 bottom-0 w-1 flex flex-col justify-around space-y-2 p-1">
                        <span class="block w-full h-1 bg-white rounded"></span>
                        <span class="block w-full h-1 bg-white rounded"></span>
                        <span class="block w-full h-1 bg-white rounded"></span>
                        <span class="block w-full h-1 bg-white rounded"></span>
                    </div>

                    <!-- Ticket content -->
                    <div class="p-6 ml-6">
                        <h2 class="text-xl font-bold">{{ $ticket->title }}</h2>
                        <p class="text-sm mt-1">Event Ticket</p>

                        <div class="mt-4 space-y-1">
                            <div class="flex justify-between"><span
                                    class="font-semibold">Date:</span><span>{{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between"><span
                                    class="font-semibold">Stadium:</span><span>{{ $ticket->stadium }}</span></div>
                            <div class="flex justify-between"><span
                                    class="font-semibold">Seat:</span><span>{{ $ticket->seat_info }}</span></div>
                            <div class="flex justify-between"><span
                                    class="font-semibold">Price:</span><span>${{ $ticket->price }}</span></div>
                            <div class="flex justify-between"><span
                                    class="font-semibold">Status:</span><span>{{ $ticket->is_available ? 'Available' : 'Sold' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex space-x-2">
                            <!-- View button visible to all -->
                            <a href="{{ route('tickets.show', $ticket->id) }}"
                                class="px-3 py-1 bg-white text-purple-600 font-semibold rounded hover:bg-gray-100">
                                View
                            </a>

                            <!-- Admin-only: Edit/Delete -->
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('tickets.edit', $ticket->id) }}"
                                    class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">
                                    Edit
                                </a>
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                        onclick="return confirm('Delete Ticket?')">
                                        Delete
                                    </button>
                                </form>
                            @endif

                            <!-- User-only: Add to Cart -->
                            @if(auth()->check() && auth()->user()->isUser() && $ticket->is_available)
                                <form action="{{ route('cart.add', $ticket->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                        Add to Cart
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Optional Ticket Footer -->
                    <div class="bg-gray-50 px-6 py-4 text-center text-gray-800 border-t border-gray-200">
                        <span class="text-sm font-semibold">Ticket ID: {{ $ticket->id }}</span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection