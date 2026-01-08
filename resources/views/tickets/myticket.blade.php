@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5 no-print">
        <a href="{{ route('football.schedule') }}" class="btn btn-dark rounded-pill px-4">
            <i class="bi bi-chevron-left me-2"></i>Back to Schedule
        </a>
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow">
            <i class="bi bi-printer-fill me-2"></i>Download / Print Ticket
        </button>
    </div>

    @foreach($tickets as $apiGameId => $matchGroup)
        @php
            $first = $matchGroup->first();
            $title = strtolower($first->home_team . ' ' . $first->away_team);
            $theme = 'default';
            if (str_contains($title, 'premier')) $theme = 'pl';
            elseif (str_contains($title, 'champions')) $theme = 'ucl';
            elseif (str_contains($title, 'world cup')) $theme = 'wc';
        @endphp

        <div class="match-section mb-5">
            <h3 class="fw-bold mb-4 no-print" style="color: var(--{{ $theme }}-color);">
                {{ $first->home_team }} vs {{ $first->away_team }}
            </h3>
            
            <div class="row g-4 justify-content-center">
                @foreach($matchGroup as $ticket)
                    <div class="col-12 col-xl-10 mb-4">
                        <div class="modern-ticket {{ $theme }}">
                            <div class="ticket-main">
                                <div class="ticket-top">
                                    <span class="badge-tournament">Official Match Entry</span>
                                    <h1 class="match-title">{{ $ticket->home_team }} <span class="text-muted">vs</span> {{ $ticket->away_team }}</h1>
                                </div>
                                
                                <div class="ticket-details">
                                    <div class="detail-group">
                                        <label>Date & Kick Off</label>
                                        <p>{{ \Carbon\Carbon::parse($ticket->match_date)->format('D d M Y • H:i') }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Stadium</label>
                                        <p>{{ $ticket->stadium }}</p>
                                    </div>
                                </div>

                                <div class="ticket-footer">
                                    <div class="seat-info-grid">
                                        <div class="info-item">
                                            <label>Stand</label>
                                            <span>{{ $ticket->stand }}</span>
                                        </div>
                                        <div class="info-item">
                                            <label>Row</label>
                                            <span>{{ $ticket->row }}</span>
                                        </div>
                                        <div class="info-item">
                                            <label>Seat</label>
                                            <span>{{ $ticket->seat_number }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ticket-stub">
                                <div class="qr-wrapper text-center">
                                    <div class="qr-code mb-2">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TICKET-{{ $ticket->id }}-USER-{{ $ticket->user_id }}" alt="QR Scan">
                                    </div>
                                    <small class="ticket-id text-uppercase">Match ID: {{ $ticket->api_game_id }}</small>
                                </div>
                                <div class="price-tag mt-3">£{{ number_format($ticket->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <hr class="my-5 no-print opacity-25">
    @endforeach
</div>

<style>
    :root {
        --pl-color: #3d195b;
        --ucl-color: #00143c;
        --wc-color: #8b6e2e;
        --default-color: #1a1a1a;
    }

    .modern-ticket {
        display: flex;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        min-height: 280px;
        position: relative;
        border: 1px solid #eee;
    }

    .modern-ticket.pl { border-top: 8px solid var(--pl-color); }
    .modern-ticket.ucl { border-top: 8px solid var(--ucl-color); }
    .modern-ticket.wc { border-top: 8px solid var(--wc-color); }
    .modern-ticket.default { border-top: 8px solid var(--default-color); }

    .ticket-main {
        flex: 3;
        padding: 35px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .badge-tournament {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #888;
        display: block;
        margin-bottom: 5px;
    }

    .match-title {
        font-weight: 900;
        font-size: 2.4rem;
        margin: 0;
        color: #000;
        letter-spacing: -1px;
        line-height: 1.1;
    }

    .ticket-details {
        display: flex;
        gap: 50px;
        margin: 20px 0;
    }

    .detail-group label, .info-item label {
        display: block;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 800;
        color: #bbb;
        margin-bottom: 4px;
    }

    .detail-group p {
        font-weight: 700;
        font-size: 1.15rem;
        margin: 0;
        color: #333;
    }

    .seat-info-grid {
        display: flex;
        background: #fdfdfd;
        padding: 15px 30px;
        border-radius: 15px;
        gap: 40px;
        border: 1px solid #f0f0f0;
    }

    .info-item span {
        font-size: 1.4rem;
        font-weight: 900;
        color: #000;
    }

    .ticket-stub {
        flex: 1;
        background: #fafafa;
        border-left: 2px dashed #ececec;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        position: relative;
    }

    .ticket-stub::before, .ticket-stub::after {
        content: '';
        position: absolute;
        left: -13px;
        width: 26px;
        height: 26px;
        background: #f8f9fa; 
        border-radius: 50%;
        border: 1px solid #eee;
    }
    .ticket-stub::before { top: -14px; }
    .ticket-stub::after { bottom: -14px; }

    .qr-code img {
        width: 130px;
        height: 130px;
        padding: 8px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .ticket-id {
        font-size: 0.65rem;
        font-family: 'Courier New', Courier, monospace;
        color: #999;
        letter-spacing: 1px;
    }

    .price-tag {
        font-weight: 800;
        font-size: 1.3rem;
        color: #222;
    }

    @media print {
        .no-print, nav, footer { display: none !important; }
        body { background: white !important; }
        .container { max-width: 100% !important; width: 100% !important; padding: 0 !important; }
        .modern-ticket { 
            box-shadow: none !important; 
            border: 1px solid #000 !important; 
            page-break-inside: avoid;
            margin-bottom: 50px;
        }
        .ticket-stub::before, .ticket-stub::after { display: none; }
    }

    @media (max-width: 992px) {
        .modern-ticket { flex-direction: column; }
        .ticket-stub { border-left: none; border-top: 2px dashed #ececec; }
        .ticket-stub::before, .ticket-stub::after { display: none; }
        .ticket-details { flex-direction: column; gap: 15px; }
    }
</style>
@endsection