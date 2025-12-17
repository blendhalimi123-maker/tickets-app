@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">🛒 Your Cart</h1>
        <span class="badge bg-primary fs-6">
            {{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4" style="font-size: 4rem;">🛍️</div>
            <h3 class="mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Browse Premier League games and select seats to get started!</p>
            <a href="{{ route('football.schedule') }}" class="btn btn-primary btn-lg">
                Browse Games <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                @foreach($cartItems as $item)
                <div class="card mb-4 shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="badge bg-secondary me-2">Premier League</span>
                                    @if($item->reserved_until)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i>
                                        Reserved: {{ $item->reserved_until->diffForHumans(['parts' => 2]) }}
                                    </span>
                                    @endif
                                </div>
                                
                                <h4 class="fw-bold mb-2">{{ $item->home_team }} vs {{ $item->away_team }}</h4>
                                
                                <div class="mb-2">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                    <strong>Date:</strong> {{ $item->match_date->format('l, F j, Y - H:i') }}
                                </div>
                                
                                <div class="mb-2">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    <strong>Venue:</strong> {{ $item->stadium }}
                                </div>
                                
                                <div class="mb-3">
                                    <i class="bi bi-ticket-perforated text-primary me-2"></i>
                                    <strong>Seat:</strong> 
                                    {{ $item->stand }} Stand, Row {{ $item->row }}, Seat {{ $item->seat_number }}
                                    <span class="badge bg-info ms-2">{{ $item->category }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Remove this seat from cart?')">
                                            <i class="bi bi-trash me-1"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="col-md-4 text-md-end">
                                <div class="mt-3 mt-md-0">
                                    <div class="text-muted mb-1">Price per seat</div>
                                    <div class="h3 fw-bold text-primary">${{ number_format($item->price, 2) }}</div>
                                    
                                    <div class="mt-3 pt-3 border-top">
                                        <div class="text-muted">Subtotal</div>
                                        <div class="h4 fw-bold">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Order Summary</h4>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items ({{ $cartItems->count() }})</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Service Fee</span>
                            <span>${{ number_format($cartItems->count() * 2.50, 2) }}</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5">
                                ${{ number_format($total + ($cartItems->count() * 2.50), 2) }}
                            </span>
                        </div>
                        
                        <button class="btn btn-success btn-lg w-100 py-3 fw-bold" disabled>
                            <i class="bi bi-lock-fill me-2"></i> Proceed to Checkout
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('football.schedule') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-3px);
    }
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
    }
</style>
@endsection