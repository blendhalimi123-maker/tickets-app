@extends('layouts.app')

@section('content')
<div class="container py-5">

    
    <div class="text-center mb-5">
        <h1 class="fw-bold">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-muted fs-5">View and buy tickets here.</p>
    </div>

    
    <div class="row g-4 mb-5 justify-content-center">

        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3">
                    <i class="bi bi-ticket-perforated-fill fs-1 text-primary"></i>
                </div>
                <h5 class="text-muted mb-2">Tickets</h5>
                <p>Browse events and purchase tickets easily.</p>
                <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-lg mt-3">View Tickets</a>
            </div>
        </div>

        
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3">
                    <i class="bi bi-box-arrow-right fs-1 text-danger"></i>
                </div>
                <h5 class="text-muted mb-2">Logout</h5>
                <p>Securely log out of your account.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg mt-3">Logout</button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
