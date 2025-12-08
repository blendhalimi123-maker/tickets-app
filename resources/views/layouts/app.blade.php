<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="d-flex flex-column min-vh-100" style="
    background:
        linear-gradient(135deg, rgba(253,251,251,0.7) 0%, rgba(235,237,238,0.7) 100%),
        url('{{ asset('images/background.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
">

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

                    @if(auth()->check() && auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tickets.create') }}">Create Ticket</a>
                    </li>
                    @endif

                    @if(auth()->check() && auth()->user()->isUser())
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            🛒 Cart
                            @php $cartCount = auth()->user()->cartCount(); @endphp
                            @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                    @endif

                    @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="settingsDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-1">⚙️</span> Settings
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="settingsDropdown" style="min-width: 250px;">
                            <li class="dropdown-header text-center">
                                <strong>{{ Auth::user()->name }}</strong><br>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="{{ route('password.change') }}">
                                    🔐 Change Password
                                </a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">🚪 Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 container my-5">
        <div class="@yield('wrapper-class', 'shadow rounded-3 p-4 border')" 
             style="@yield('wrapper-style', 'background: rgba(255,255,255,0.85); backdrop-filter: blur(3px);')">
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
