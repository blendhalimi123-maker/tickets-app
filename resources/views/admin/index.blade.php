@extends('layouts.app')

@section('content')
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
                </div>
            </a>
        </div>
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

</div>
@endsection
