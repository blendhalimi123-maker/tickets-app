@extends('layouts.app')

@section('wrapper-class', '')
@section('wrapper-style', '')

@section('content')
<style>
    .hero-section {
        text-align: center;
        margin-bottom: 50px;
    }

    .hero-title {
        font-size: 2.6rem;
        font-weight: 700;
        color: #333;
    }

    .hero-sub {
        color: #555;
        margin-top: 5px;
    }

    .bubble {
        position: absolute;
        border-radius: 50%;
        opacity: 0.25;
        filter: blur(20px);
        z-index: -1;
        animation: float 9s infinite ease-in-out;
    }

    .b1 { width: 280px; height: 280px; background:#ffafbd; top: -40px; left: 40px; }
    .b2 { width: 320px; height: 320px; background:#c9ffbf; bottom: 20px; right: 50px; animation-delay: 2s; }
    .b3 { width: 220px; height: 220px; background:#a1c4fd; top: 200px; right: 200px; animation-delay: 4s; }

    @keyframes float {
        50% { transform: translateY(-25px) translateX(20px); }
    }

    .soft-card {
        background: white;
        border-radius: 22px;
        padding: 35px;
        box-shadow: 0px 10px 35px rgba(0, 0, 0, 0.10);
        transition: .25s ease;
    }

    .soft-card:hover {
        transform: translateY(-8px);
        box-shadow: 0px 16px 45px rgba(0, 0, 0, 0.14);
    }

    .icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 18px;
        font-size: 2rem;
        color: white;
    }

    .tickets-bg { background: linear-gradient(135deg, #a18cd1, #fbc2eb); }
    .schedule-bg { background: linear-gradient(135deg, #84fab0, #8fd3f4); }

    .btn-modern {
        border: none;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 600;
        background: #4facfe;
        color: white;
        transition: .2s ease;
    }

    .btn-modern:hover {
        background: #00f2fe;
        transform: translateY(-3px);
    }
</style>

<div class="container position-relative">

    <div class="bubble b1"></div>
    <div class="bubble b2"></div>
    <div class="bubble b3"></div>

    <div class="hero-section mt-4">
        <h1 class="hero-title">Welcome, {{ auth()->user()->name }} 👋</h1>
        <p class="hero-sub">Explore your tickets, schedules and upcoming events</p>
    </div>

    <div class="row g-4 justify-content-center">

        <div class="col-md-6 col-lg-4">
            <div class="soft-card text-center">
                <div class="icon-wrap tickets-bg">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>
                <h4 class="fw-bold mb-2">Tickets</h4>
                <p class="text-muted mb-4">Find, buy and manage tickets easily.</p>
                <a href="{{ route('tickets.index') }}" class="btn-modern w-100">View Tickets</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="soft-card text-center">
                <div class="icon-wrap schedule-bg">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <h4 class="fw-bold mb-2">Team Schedule</h4>
                <p class="text-muted mb-4">View all upcoming matches.</p>
                <a href="{{ route('football.schedule') }}" class="btn-modern w-100">View Schedule</a>
            </div>
        </div>

    </div>

</div>
@endsection
