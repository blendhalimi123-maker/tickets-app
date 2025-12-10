@extends('layouts.app')

@section('content')

<style> :root { --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); --success-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); --warning-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%); } .dashboard-hero { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 24px; padding: 3rem 2rem; margin-bottom: 3rem; position: relative; overflow: hidden; } .dashboard-hero::before { content: ''; position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: var(--primary-gradient); opacity: 0.1; border-radius: 50%; transform: translate(100px, -100px); } .dashboard-hero::after { content: ''; position: absolute; bottom: 0; left: 0; width: 200px; height: 200px; background: var(--success-gradient); opacity: 0.1; border-radius: 50%; transform: translate(-80px, 80px); } .welcome-title { font-size: 2.8rem; font-weight: 800; background: var(--primary-gradient); -webkit-background-clip: text; background-clip: text; color: transparent; margin-bottom: 0.5rem; } .welcome-subtitle { font-size: 1.1rem; color: #6c757d; max-width: 500px; margin: 0 auto; } .user-avatar { width: 60px; height: 60px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 600; margin: 0 auto 1.5rem; } .dashboard-card { background: white; border-radius: 20px; padding: 2rem; height: 100%; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease; position: relative; overflow: hidden; } .dashboard-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); } .dashboard-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: var(--primary-gradient); } .card-icon { width: 70px; height: 70px; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; font-size: 1.8rem; color: white; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); } .card-tickets { background: var(--primary-gradient); } .card-schedule { background: var(--warning-gradient); } .card-events { background: var(--success-gradient); } .card-stats { background: var(--dark-gradient); } .card-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 0.75rem; color: #2c3e50; } .card-desc { color: #6c757d; font-size: 0.95rem; line-height: 1.5; margin-bottom: 1.5rem; } .btn-gradient { background: var(--primary-gradient); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; transition: all 0.3s ease; width: 100%; } .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3); color: white; } .stats-badge { position: absolute; top: 1rem; right: 1rem; background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; } .recent-events { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 2rem; color: white; margin-top: 2rem; } .recent-events h5 { color: white; margin-bottom: 1rem; } .event-item { background: rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 12px; margin-bottom: 0.75rem; backdrop-filter: blur(10px); } @media (max-width: 768px) { .welcome-title { font-size: 2.2rem; } .dashboard-hero { padding: 2rem 1rem; } } </style><div class="container py-4 py-lg-5"> <div class="dashboard-hero text-center position-relative z-1"> <div class="user-avatar"> {{ substr(auth()->user()->name, 0, 1) }} </div> <h1 class="welcome-title">Welcome back, {{ auth()->user()->name }}! 👋</h1> <p class="welcome-subtitle"> Manage your tickets, check upcoming matches, and stay updated with all football events in one place. </p> </div>

<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="text-center p-3 bg-white rounded-3 shadow-sm">
            <div class="fs-2 fw-bold text-primary">12</div>
            <div class="text-muted small">Upcoming Matches</div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="text-center p-3 bg-white rounded-3 shadow-sm">
            <div class="fs-2 fw-bold text-success">5</div>
            <div class="text-muted small">Your Tickets</div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="text-center p-3 bg-white rounded-3 shadow-sm">
            <div class="fs-2 fw-bold text-warning">3</div>
            <div class="text-muted small">Teams Following</div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="text-center p-3 bg-white rounded-3 shadow-sm">
            <div class="fs-2 fw-bold text-info">2</div>
            <div class="text-muted small">Notifications</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="dashboard-card">
            <span class="stats-badge">New</span>
            <div class="card-icon card-tickets">
                <i class="bi bi-ticket-detailed"></i>
            </div>
            <h3 class="card-title">Manage Tickets</h3>
            <p class="card-desc">
                Browse, purchase, and manage all your match tickets in one place. 
                Get instant access to your bookings and download e-tickets.
            </p>
            <a href="{{ route('tickets.index') }}" class="btn btn-gradient">
                <i class="bi bi-arrow-right me-2"></i> View All Tickets
            </a>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="card-icon card-schedule">
                <i class="bi bi-calendar-week"></i>
            </div>
            <h3 class="card-title">Match Schedule</h3>
            <p class="card-desc">
                View complete fixtures, match times, and venues. 
                Never miss a game with real-time updates and notifications.
            </p>
            <a href="{{ route('football.schedule') }}" class="btn btn-gradient">
                <i class="bi bi-calendar3 me-2"></i> View Schedule
            </a>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="card-icon card-events">
                <i class="bi bi-megaphone"></i>
            </div>
            <h3 class="card-title">Latest Events</h3>
            <p class="card-desc">
                Stay updated with the latest football events, 
                tournaments, and special promotions.
            </p>
            <button class="btn btn-gradient">
                <i class="bi bi-bell me-2"></i> Explore Events
            </button>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="card-icon card-stats">
                <i class="bi bi-graph-up"></i>
            </div>
            <h3 class="card-title">Statistics</h3>
            <p class="card-desc">
                Track your booking history, favorite teams, 
                and spending patterns with detailed analytics.
            </p>
            <button class="btn btn-gradient">
                <i class="bi bi-pie-chart me-2"></i> View Stats
            </button>
        </div>
    </div>
</div>

<div class="recent-events mt-5">
    <h5 class="fw-bold">Upcoming Matches</h5>
    <div class="event-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Premier League</strong>
                <div class="small">Chelsea vs Everton • Sat, Dec 13</div>
            </div>
            <span class="badge bg-light text-dark">07:00 AM</span>
        </div>
    </div>
    <div class="event-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Champions League</strong>
                <div class="small">Real Madrid vs Bayern • Tue, Dec 16</div>
            </div>
            <span class="badge bg-light text-dark">08:00 PM</span>
        </div>
    </div>
    <div class="event-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Premier League</strong>
                <div class="small">Arsenal vs Wolverhampton • Sat, Dec 13</div>
            </div>
            <span class="badge bg-light text-dark">12:00 PM</span>
        </div>
    </div>
</div>
</div><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"><script> document.addEventListener('DOMContentLoaded', function() { const cards = document.querySelectorAll('.dashboard-card'); cards.forEach(card => { card.addEventListener('mouseenter', function() { this.style.transform = 'translateY(-8px)'; }); card.addEventListener('mouseleave', function() { this.style.transform = 'translateY(0)'; }); }); }); </script>
@endsection