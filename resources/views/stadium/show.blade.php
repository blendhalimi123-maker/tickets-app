@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">VIP Stadium Seats (Max 5 per match)</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach(range('A','T') as $row)
        <div class="mb-3">
            <strong>Row {{ $row }}</strong>
            <div class="d-flex flex-wrap">
                @foreach(range(1,20) as $number)
                    @php
                        $seat = $seats->first(fn($s) => $s->row == $row && $s->number == $number);
                        $is_cart = in_array($seat->id, $cart_seats ?? []);
                        $disabled = $seat->is_booked || $is_cart;
                    @endphp

                    <form method="POST" action="{{ route('cart.add', $fixture_id) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="seat_id" value="{{ $seat->id }}">
                        <button type="submit"
                            class="btn btn-sm me-1 mb-1 {{ $disabled ? 'btn-secondary' : 'btn-success' }}"
                            {{ $disabled ? 'disabled' : '' }}>
                            {{ $number }}
                        </button>
                
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
