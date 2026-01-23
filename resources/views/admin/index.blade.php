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
            
            <div class="row justify-content-center mt-5">
                <div class="col-md-10 text-start">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Live Activity Alerts</h5>
                            <span class="badge bg-danger animate-pulse" id="live-indicator">LIVE</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Time</th>
                                            <th>Type</th>
                                            <th>Detail</th>
                                            <th>User/Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-sales-table-body">
                                        <tr id="no-sales-yet">
                                            <td colspan="4" class="text-center py-4 text-muted">Waiting for activity...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
    </div>
</div>

<audio id="admin-alert-sound" src="{{ asset('sounds/sale-alert.mp3') }}" preload="auto"></audio>

<style>
    @keyframes flash-gold {
        0% { background-color: #fff3cd; }
        100% { background-color: transparent; }
    }
    .new-sale-row {
        animation: flash-gold 3s ease-out;
    }
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Echo !== 'undefined') {
            
            Echo.private('admin-channel')
                .listen('TicketPurchased', (e) => {
                    handleNewActivity('TICKET', e.adminData.match, e.adminData.customer, `£${e.adminData.total}`, 'text-success fw-bold');
                });

            Echo.channel('sales-channel')
                .listen('UserRegistered', (e) => {
                    handleNewActivity('NEW USER', 'Joined the club', e.adminData.name, e.adminData.email, 'text-muted small');
                });

            function handleNewActivity(type, detail, user, extra, extraClass) {
                const noSalesRow = document.getElementById('no-sales-yet');
                if (noSalesRow) noSalesRow.remove();

                const audio = document.getElementById('admin-alert-sound');
                if (audio) {
                    audio.play().catch(err => console.log("User interaction needed for audio"));
                }

                const tableBody = document.getElementById('admin-sales-table-body');
                const badgeClass = type === 'TICKET' ? 'bg-warning text-dark' : 'bg-info text-dark';
                
                const row = `
                    <tr class="new-sale-row">
                        <td class="fw-bold text-muted small">${new Date().toLocaleTimeString()}</td>
                        <td><span class="badge ${badgeClass}">${type}</span></td>
                        <td>${detail}</td>
                        <td>
                            <div>${user}</div>
                            <div class="${extraClass}">${extra}</div>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('afterbegin', row);
            }
        }
    });
</script>
@endsection