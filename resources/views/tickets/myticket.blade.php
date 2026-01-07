@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ route('football.schedule') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Schedule
        </a>
        <button onclick="window.print()" class="btn btn-primary shadow-sm">
            <i class="bi bi-printer"></i> Print All Tickets
        </button>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($tickets as $ticket)
            @php
                $themeClass = 'default-ticket';
                $title = strtolower($ticket->title);
                
                if (str_contains($title, 'premier league')) {
                    $themeClass = 'pl-theme';
                } elseif (str_contains($title, 'champions league')) {
                    $themeClass = 'ucl-theme';
                } elseif (str_contains($title, 'world cup')) {
                    $themeClass = 'wc-theme';
                }
            @endphp

            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden ticket-card {{ $themeClass }}">
                    
                    <div class="p-4 text-center ticket-header">
                        <div class="tournament-logo mb-2"></div>
                        <h2 class="fw-bold mb-1" style="font-size: 1.8rem;">
                            {{ $ticket->title }}
                        </h2>
                        <small class="d-block text-uppercase tracking-wider">Official Match Ticket</small>
                    </div>

                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Date & Time</small>
                                    <strong>{{ \Carbon\Carbon::parse($ticket->game_date)->format('M d, Y H:i') }}</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Venue</small>
                                    <strong>{{ $ticket->stadium }}</strong>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Seat</small>
                                        <strong>{{ $ticket->seat_info }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Price</small>
                                        <strong>£{{ $ticket->price }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-center border-start">
                                <div class="barcode-container mb-2">
                                    <div class="barcode"></div>
                                </div>
                                <small class="text-muted">ID: {{ $ticket->id }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="bg-light px-4 py-2 text-center text-muted border-top border-dashed" 
                         style="border-style: dashed !important;">
                        Please present this ticket at the stadium entrance.
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>


    .pl-theme .ticket-header { background: linear-gradient(135deg, #3d195b, #7b2cbf); color: white; }
    .pl-theme .tournament-logo::after { content: "⚽ PREMIER LEAGUE"; font-weight: bold; }

    .ucl-theme .ticket-header { background: linear-gradient(135deg, #00143c, #004494); color: white; }
    .ucl-theme .tournament-logo::after { content: "⭐ CHAMPIONS LEAGUE"; font-weight: bold; }

    .wc-theme .ticket-header { background: linear-gradient(135deg, #8b6e2e, #014c24); color: white; }
    .wc-theme .tournament-logo::after { content: "🏆 WORLD CUP"; font-weight: bold; }

    .default-ticket .ticket-header { background: linear-gradient(135deg, #cfe2ff, #9ec5fe); color: #084298; }

    .ticket-card { border-left: 8px solid transparent; }
    .pl-theme { border-left-color: #3d195b; }
    .ucl-theme { border-left-color: #00143c; }
    .wc-theme { border-left-color: #8b6e2e; }

    .barcode {
        width: 100%;
        height: 60px;
        background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px);
        margin: 0 auto;
    }

    @media print {
        .no-print, nav, footer { display: none !important; }
        body { background: white !important; padding: 0; }
        .container { width: 100%; max-width: none; }
        .ticket-card { box-shadow: none !important; border: 1px solid #ccc; page-break-inside: avoid; margin-bottom: 20px; }
    }
</style>
@endsection