<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets App</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold fs-3" href="{{ route('tickets.index') }}">TicketsApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tickets.index') }}">Home</a>
                    </li>

                    <!-- Admin-only: Create Ticket -->
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tickets.create') }}">Create Ticket</a>
                    </li>
                    @endif

                    <!-- User-only: Shopping Cart -->
                    @if(auth()->check() && auth()->user()->isUser())
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            🛒 Cart
                            @php
                                $cartCount = auth()->user()->cartCount();
                            @endphp
                            @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                    @endif

                  
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link p-0">Logout</button>
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="flex-grow-1 container my-5">
        <div class="bg-white shadow rounded-3 p-4 border">
            @yield('content')
        </div>
    </main>

    
    <footer class="bg-light text-center text-muted py-4 mt-auto">
        &copy; {{ date('Y') }} Tickets App. All rights reserved.
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
