@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #eef2ff, #f8f9ff);
    }

    .cart-card {
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(6px);
        transition: .25s ease-in-out;
        border-radius: 18px !important;
    }
    .cart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.12);
    }

    .summary-card {
        background: linear-gradient(135deg, #ffffff, #eef1ff);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .quantity-box {
        width: 70px;
        border-radius: 8px;
    }

    .price-tag {
        font-size: 1.4rem;
        color: #2d3748;
    }

    .divider {
        height: 2px;
        background: linear-gradient(90deg, #6c63ff, #42a5f5);
        width: 70px;
        margin: 12px 0;
        border-radius: 6px;
    }
</style>

<div class="container py-5">

    <h1 class="fw-bold text-center mb-2">🛒 Your Shopping Cart</h1>
    <p class="text-center text-muted mb-5">Review your items before checkout</p>

    @if(session('success'))
        <div class="alert alert-success text-center shadow-sm">{{ session('success') }}</div>
    @endif

    @if($cartItems->isEmpty())

        <div class="alert alert-info text-center fw-semibold shadow-sm p-4 rounded-4">
            Your cart is empty.  
            <a href="{{ route('tickets.index') }}" class="text-decoration-underline fw-bold">
                Browse tickets
            </a>
        </div>

    @else

        @php $total = 0; @endphp

        <div class="row g-4">

            @foreach($cartItems as $cart)
                @php 
                    $ticket = $cart->ticket;
                    $itemTotal = $ticket->price * $cart->quantity;
                    $total += $itemTotal;
                @endphp

                <div class="col-12">
                    <div class="cart-card shadow-sm p-4 d-flex flex-column flex-md-row align-items-md-center">

                        <div class="flex-grow-1">
                            <h4 class="fw-bold">{{ $ticket->title }}</h4>
                            <div class="divider"></div>

                            <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> 
                                {{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}
                            </p>

                            <p class="mb-1 text-muted"><i class="bi bi-geo-alt"></i> {{ $ticket->stadium }}</p>
                            <p class="mb-1 text-muted"><i class="bi bi-door-open"></i> Seat: {{ $ticket->seat_info }}</p>

                            <p class="fw-semibold mt-2 text-dark">Price per ticket: ${{ $ticket->price }}</p>
                        </div>

                        <div class="d-flex flex-column align-items-center gap-2 mt-3 mt-md-0">

                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1"
                                       class="form-control quantity-box text-center">
                                <button type="submit" class="btn btn-outline-primary btn-sm px-3">Update</button>
                            </form>

                            <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3"
                                        onclick="return confirm('Remove this ticket from cart?')">
                                    Remove
                                </button>
                            </form>
                        </div>

                        <div class="ms-md-4 mt-3 mt-md-0 text-md-end text-center">
                            <span class="fw-bold price-tag">${{ $itemTotal }}</span>
                        </div>

                    </div>
                </div>

            @endforeach
        </div>

        <div class="d-flex justify-content-end mt-5">
            <div class="summary-card p-4 shadow-sm" style="max-width: 350px;">
                <h4 class="fw-bold">Order Summary</h4>

                <p class="mt-3 mb-1">Items: <span class="fw-semibold">{{ $cartItems->count() }}</span></p>
                <p class="fs-4 fw-bold text-dark">Total: ${{ $total }}</p>

                <a href="#" class="btn btn-success w-100 fw-bold py-2 mt-2">
                    Proceed to Checkout
                </a>
            </div>
        </div>

    @endif
</div>

@endsection
