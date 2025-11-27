@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-lg rounded-4 position-relative" style="max-width: 700px; width: 100%; overflow: hidden;">

      
        <div class="text-center text-white py-5" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
            <h1 class="fw-bold display-3 mb-3">{{ $ticket->title }}</h1>
            <p class="mb-2 fs-5">{{ \Carbon\Carbon::parse($ticket->game_date)->format('l, M d, Y H:i') }}</p>
            <p class="mb-0 fs-5">{{ $ticket->stadium }}</p>
        </div>

        
        <div class="p-5 text-center bg-light">

            <div class="mb-4">
                <h4 class="fw-bold">Seat</h4>
                <p class="fs-5">{{ $ticket->seat_info }}</p>
            </div>

            <div class="mb-4">
                <h4 class="fw-bold">Price</h4>
                <p class="fs-5">${{ $ticket->price }}</p>
            </div>

            <div class="mb-4">
                <h4 class="fw-bold">Status</h4>
                <span class="badge {{ $ticket->is_available ? 'bg-success' : 'bg-danger' }} fs-6 p-2">
                    {{ $ticket->is_available ? 'Available' : 'Sold' }}
                </span>
            </div>
        </div>

        
        <div class="position-absolute" style="bottom: 20px; right: 20px;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ route('tickets.show', $ticket->id) }}" 
                 alt="QR Code" class="img-fluid rounded shadow-sm">
        </div>

        
        <div class="bg-secondary text-white text-center py-2 mt-3" style="border-top: 2px dashed #fff;">
            Ticket ID: {{ $ticket->id }}
        </div>
    </div>
</div>


<style>
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 30px rgba(0,0,0,0.25);
    transition: all 0.3s ease;
}
</style>
@endsection
