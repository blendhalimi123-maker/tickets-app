@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow border-0 py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold">Payment Successful!</h2>
                    <p class="text-muted mb-4 lead">
                        Your seats are now confirmed. You can find your tickets in the "My Tickets" section.
                    </p>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('tickets.my', ['id' => $id]) }}" class="btn btn-primary btn-lg px-4 me-2">
                            <i class="bi bi-ticket-perforated me-2"></i>View My Tickets
                        </a>
                        <a href="{{ route('football.schedule') }}" class="btn btn-outline-secondary btn-lg px-4">
                           Back to Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection