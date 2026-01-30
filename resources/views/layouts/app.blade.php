<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tickets App')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/js/app.js'])

    <style>
        :root {
            --primary-color: #38003c;
            --secondary-color: #00ff85;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            padding-top: 70px;
        }

        /* ================= ADMIN ================= */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: #1e293b;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .admin-content {
            margin-left: var(--sidebar-width);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #334155;
            color: white;
        }

        /* ================= USER NAVBAR ================= */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-brand-container {
            background: var(--primary-color);
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }

        .cart-badge {
            font-size: 11px;
            position: absolute;
            top: -5px;
            right: -8px;
        }

        .main-content {
            padding: 2rem;
        }
    </style>
</head>

<body>

{{-- ================= ADMIN LAYOUT ================= --}}
@if(auth()->check() && auth()->user()->isAdmin())

<div class="admin-sidebar">

    <div class="p-3 border-bottom">
        <strong>Admin Panel</strong>
    </div>

    <ul class="sidebar-menu flex-grow-1">

        <li>
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
        </li>

        <li>
            <a href="{{ route('admin.index') }}">Home</a>
        </li>

        <li>
            <a href="{{ url('/admin/users') }}">Users</a>
        </li>

        <li>
            <a href="{{ route('password.change') }}">Settings</a>
        </li>
    </ul>

    <div class="p-3 border-top">
        {{ auth()->user()->name }}

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-danger w-100 mt-2">Logout</button>
        </form>
    </div>
</div>

{{-- ================= USER / GUEST NAVBAR ================= --}}
@else

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">

        <a class="navbar-brand-container" href="{{ route('user.dashboard') }}">
            TICKETSAPP
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">

            @auth

                {{-- CART --}}
                @if(auth()->user()->isUser())
                <div class="position-relative">
                    <a href="{{ route('cart.index') }}" class="nav-link">
                        Cart
                        @php $count = auth()->user()->cartCount(); @endphp
                        @if($count > 0)
                            <span class="badge bg-danger rounded-pill cart-badge">{{ $count }}</span>
                        @endif
                    </a>
                </div>
                @endif

                {{-- DROPDOWN --}}
                <div class="dropdown">
                    <a class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('password.change') }}">
                                Settings
                            </a>
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link">Register</a>
            @endauth

        </div>
    </div>
</nav>

@endif


<main class="main-content admin-content">
    @yield('content')
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
