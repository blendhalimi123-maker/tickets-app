@extends('layouts.app')

@section('content')

<style>
.dashboard-wrapper {
    padding: 1rem;
}
.hero-section {
    text-align: center;
    margin-bottom: 1.5rem;
}
.welcome-text {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}
.subtitle {
    color: #666;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}
.grid-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
.card-modern {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #f0f0f0;
}
.card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}
.icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}
.icon-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.icon-green {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
.icon-orange {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}
.icon-blue {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}
.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}
.card-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #1a1a1a;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s ease;
}
.btn-action:hover {
    background: #333;
    color: white;
}
.btn-secondary {
    background: #f8f9fa;
    color: #333;
    border: 1px solid #e5e7eb;
}
.btn-secondary:hover {
    background: #e9ecef;
    color: #333;
}
.dashboard-image {
    margin-top: 1rem;
}
.dashboard-image img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 12px;
    display: block;
}
</style>

<div class="dashboard-wrapper">
    <div class="hero-section">
        @auth
            <h1 class="welcome-text">Welcome, {{ auth()->user()->name }}</h1>
        @endauth
        @guest
            <h1 class="welcome-text">Football Tickets Dashboard</h1>
        @endguest
        <p class="subtitle">Manage tickets, view schedules, and stay updated with football events</p>
    </div>

    <!-- <div class="grid-cards">
        <div class="card-modern">
            <div class="card-header">
                <div class="icon-circle icon-purple">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <h3 class="card-title">Tickets</h3>
            </div>
            <p class="card-description">Purchase and manage your match tickets. View your bookings and download e-tickets.</p>
            <a href="{{ route('tickets.index') }}" class="btn-action">
                <i class="bi bi-ticket-perforated"></i> View Tickets
            </a>
        </div> -->
        <div class="crds d-flex gap-4 flex-wrap px-0">
            <div class="card-modern flex-grow-1">
                <div class="card-header">
                    <div class="icon-circle icon-green">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <h3 class="card-title">Schedule</h3>
                </div>
                <p class="card-description">Check match fixtures, times, and venues. Never miss a game with updates.</p>
                <a href="{{ route('football.schedule') }}" class="btn-action">
                    <i class="bi bi-calendar-week"></i> View Schedule
                </a>
            </div>

            <div class="card-modern flex-grow-1">
                <div class="card-header">
                    <div class="icon-circle icon-orange">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h3 class="card-title">Events</h3>
                </div>
                <p class="card-description">Stay updated with latest football events, tournaments, and promotions.</p>
                <a href="{{ route('football.events') }}" class="btn-action btn-secondary">
                    <i class="bi bi-megaphone"></i> Explore
                </a>
            </div>

            <div class="card-modern flex-grow-1">
                <div class="card-header">
                    <div class="icon-circle icon-blue">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3 class="card-title">Statistics</h3>
                </div>
                <p class="card-description">Track your booking history and favorite teams with analytics.</p>
                <button class="btn-action btn-secondary" onclick="showStats()">
                    <i class="bi bi-graph-up"></i> View Stats
                </button>
            </div>
        </div>
    </div>

    <div class="dashboard-image">
        <img src="{{ asset('images/test.jpg') }}" alt="Premier League Players">
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
function showEvents() {
    alert('Events feature coming soon');
}

function showStats() {
    alert('Statistics feature coming soon');
}
</script>

@endsection