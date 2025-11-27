@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Your Shopping Cart</h1>

    <!-- Success message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="p-6 bg-yellow-100 text-yellow-800 rounded">
            Your cart is empty. <a href="{{ route('tickets.index') }}" class="underline text-blue-600">Browse tickets</a>.
        </div>
    @else
        <div class="space-y-6">

            @php $total = 0; @endphp

            @foreach($cartItems as $cart)
                @php 
                    $ticket = $cart->ticket;
                    $itemTotal = $ticket->price * $cart->quantity;
                    $total += $itemTotal;
                @endphp

                <div class="flex justify-between items-center bg-white shadow rounded-lg p-4">
                    
                    <!-- Ticket info -->
                    <div>
                        <h2 class="text-xl font-bold">{{ $ticket->title }}</h2>
                        <p class="text-sm text-gray-600">Date: {{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}</p>
                        <p class="text-sm text-gray-600">Stadium: {{ $ticket->stadium }}</p>
                        <p class="text-sm text-gray-600">Seat: {{ $ticket->seat_info }}</p>
                        <p class="text-sm font-semibold text-gray-800">Price: ${{ $ticket->price }}</p>
                    </div>

                    <!-- Quantity & actions -->
                    <div class="flex items-center space-x-2">
                        <!-- Update quantity form -->
                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="w-16 p-1 border rounded text-center">
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                        </form>

                        <!-- Remove from cart -->
                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Remove this ticket from cart?')">
                                Remove
                            </button>
                        </form>
                    </div>

                    <!-- Item total -->
                    <div class="text-right font-semibold">
                        ${{ $itemTotal }}
                    </div>
                </div>
            @endforeach

            <!-- Cart total -->
            <div class="flex justify-end mt-6">
                <div class="text-xl font-bold">
                    Total: ${{ $total }}
                </div>
            </div>

            <!-- Checkout button -->
            <div class="flex justify-end mt-4">
                <a href="#" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
