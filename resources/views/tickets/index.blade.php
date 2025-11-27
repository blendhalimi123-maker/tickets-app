@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center fw-bold mb-5">Tickets Available</h1>

    <div class="row g-4">
        @foreach($tickets as $ticket)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                    <!-- Ticket Header -->
                    <div class="p-4 text-center text-dark" 
                         style="background: linear-gradient(135deg, #cfe2ff, #9ec5fe);">
                        <h2 class="fw-bold mb-2" style="font-size: 1.8rem;">
                            {{ $ticket->title }}
                        </h2>
                        <small class="d-block mb-1">{{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}</small>
                        <small class="d-block">{{ $ticket->stadium }}</small>
                    </div>

                    <!-- Ticket Body -->
                    <div class="p-4 text-center">
                        <div class="mb-3"><strong>Seat:</strong> {{ $ticket->seat_info }}</div>
                        <div class="mb-3"><strong>Price:</strong> ${{ $ticket->price }}</div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge {{ $ticket->is_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $ticket->is_available ? 'Available' : 'Sold' }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-primary btn-sm">View</a>

                            @if(auth()->check() && auth()->user()->role === 'user' && $ticket->is_available)
                                <form action="{{ route('cart.add', $ticket->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                                </form>
                            @endif

                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this ticket?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Footer -->
                    <div class="bg-light px-4 py-2 text-center text-muted border-top border-dashed" 
                         style="border-style: dashed !important;">
                        Ticket ID: {{ $ticket->id }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($tickets->isEmpty())
        <p class="text-center text-muted mt-5">No tickets available at the moment.</p>
    @endif
</div>
@endsection
