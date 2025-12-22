@extends('layouts.app')

@section('content')
<div class="container">
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
            <p class="text-muted mb-4">Use the main navigation to manage your application</p>
            
            <!-- <div class="row justify-content-center">
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
                                    <h3 class="fw-bold text-success">{{ $bookedTickets ?? 0 }}</h3>
                                    <p class="text-muted small mb-0">Booked Tickets</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection