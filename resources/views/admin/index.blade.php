@extends('layouts.app')

@section('content')
<<<<<<< HEAD
<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Welcome Admin, {{ auth()->user()->name }}</h1>
        <p class="text-muted">Manage tickets and view all system activity here.</p>
    </div>

    <div class="row g-4 mb-5">
        {{-- ////////////////////////////// --}}
        {{--
        <div class="col-md-6">
            <a href="{{ route('tickets.index') }}" class="card shadow-sm border-0 text-center text-decoration-none p-4 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Manage Tickets</h5>
                    <p class="card-text text-muted">Create, edit, and delete tickets.</p>
=======
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-3 col-lg-2 px-0">
            <div class="bg-dark text-white vh-100 position-fixed" style="width: 250px;">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i>Admin Panel
                    </h5>
                    <p class="small text-light mt-1 mb-0">Welcome, {{ auth()->user()->name }}</p>
>>>>>>> e6680a0cba488afe038c406a86dcd800e3ed15d8
                </div>
                
                <ul class="nav flex-column p-3">
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-white active">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('tickets.index') }}" class="nav-link text-white">
                            <i class="fas fa-ticket-alt me-2"></i>Manage Tickets
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.schedules') }}" class="nav-link text-white">
                            <i class="fas fa-calendar-alt me-2"></i>Manage Schedules
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.users') }}" class="nav-link text-white">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </li>
                </ul>
                
                <div class="p-3 border-top">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
<<<<<<< HEAD
        --}}
    </div>

    <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-lg">
                Logout
            </button>
        </form>
    </div>

=======
        
        <div class="col-md-9 col-lg-10 ms-auto">
            <div class="p-4">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                    </h3>
                    <div class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ now()->format('F j, Y') }}
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <div class="text-center py-5">
                    <div class="display-1 mb-4">
                        <i class="fas fa-user-shield text-primary"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Welcome to Admin Dashboard</h2>
                    <p class="text-muted mb-4">Select an option from the sidebar to get started</p>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Quick Stats</h5>
                                    <div class="row">
                                        <div class="col-6 border-end">
                                            <h3 class="fw-bold text-primary">{{ $totalUsers ?? 0 }}</h3>
                                            <p class="text-muted small mb-0">Total Users</p>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="fw-bold text-success">{{ $activeTickets ?? 0 }}</h3>
                                            <p class="text-muted small mb-0">Active Tickets</p>
                                        </div>
                                    </div>
                                </div>
                            </div>ss
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
>>>>>>> e6680a0cba488afe038c406a86dcd800e3ed15d8
</div>

<style>
.position-fixed {
    position: fixed;
}
.vh-100 {
    height: 100vh;
}
</style>

@endsection