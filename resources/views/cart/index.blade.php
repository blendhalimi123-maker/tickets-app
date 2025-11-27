@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center fw-bold mb-5">Your Shopping Cart</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <div class="alert alert-warning text-center">
            Your cart is empty. <a href="{{ route('tickets.index') }}" class="text-decoration-underline">Browse tickets</a>.
        </div>
    @else
        <div class="row g-4">
            @php $total = 0; @endphp

            @foreach($cartItems as $cart)
                @php 
                    $ticket = $cart->ticket;
                    $itemTotal = $ticket->price * $cart->quantity;
                    $total += $itemTotal;
                @endphp

                <div class="col-12">
                    <div class="card shadow-sm rounded-4 border-0 p-3 d-flex flex-column flex-md-row align-items-center">
                        
                        <!-- Ticket Info -->
                        <div class="flex-grow-1">
                            <h4 class="fw-bold mb-1">{{ $ticket->title }}</h4>
                            <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}</p>
                            <p class="mb-1 text-muted"><i class="bi bi-geo-alt"></i> {{ $ticket->stadium }}</p>
                            <p class="mb-1 text-muted"><i class="bi bi-door-open"></i> Seat: {{ $ticket->seat_info }}</p>
                            <p class="fw-semibold mb-0">Price: ${{ $ticket->price }}</p>
                        </div>

                        <!-- Quantity & Actions -->
                        <div class="d-flex flex-column align-items-center gap-2 mt-3 mt-md-0">
                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="form-control form-control-sm" style="width:70px;">
                                <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                            </form>

                            <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove this ticket from cart?')">
                                    Remove
                                </button>
                            </form>
                        </div>

                        <!-- Item Total -->
                        <div class="ms-md-3 mt-3 mt-md-0 text-end fw-bold fs-5">
                            ${{ $itemTotal }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="d-flex justify-content-end mt-4">
            <div class="card shadow-sm p-4 rounded-4" style="max-width: 320px; background: #f8f9fa;">
                <h5 class="fw-bold mb-3">Cart Summary</h5>
                <p class="mb-1">Items: {{ $cartItems->count() }}</p>
                <p class="mb-3 fs-5 fw-semibold">Total: ${{ $total }}</p>
                <a href="#" class="btn btn-success w-100 fw-bold">Proceed to Checkout</a>
            </div>
        </div>
    @endif
</div>
@endsection
