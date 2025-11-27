@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Welcome Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold">Welcome Admin, {{ auth()->user()->name }}</h1>
        <p class="text-muted">Manage tickets and view all system activity here.</p>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <a href="{{ route('tickets.index') }}" class="card shadow-sm border-0 text-center text-decoration-none p-4 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Manage Tickets</h5>
                    <p class="card-text text-muted">Create, edit, and delete tickets.</p>
                </div>
            </a>
        </div>
        {{-- Uncomment if you want a "Manage Users" card in the future --}}
        {{-- <div class="col-md-6">
            <a href="{{ route('admin.users') }}" class="card shadow-sm border-0 text-center text-decoration-none p-4 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Manage Users</h5>
                    <p class="card-text text-muted">View and edit system users.</p>
                </div>
            </a>
        </div> --}}
    </div>

    <!-- Logout Button -->
    <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-lg">
                Logout
            </button>
        </form>
    </div>

</div>
@endsection
