<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pusher-key" content="{{ env('PUSHER_APP_KEY') }}">
    <meta name="pusher-cluster" content="{{ env('PUSHER_APP_CLUSTER') }}">
    <meta name="reverb-key" content="{{ env('REVERB_APP_KEY') }}">
    <meta name="reverb-host" content="{{ env('REVERB_HOST') }}">
    <meta name="reverb-port" content="{{ env('REVERB_PORT') }}">
    <meta name="reverb-scheme" content="{{ env('REVERB_SCHEME') }}">
    <title>@yield('title', 'Tickets App')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #38003c;
            --secondary-color: #00ff85;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(253,251,251,0.9) 0%, rgba(235,237,238,0.9) 100%),
                        url('{{ asset('images/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            padding-top: 70px;
        }

        @if(auth()->check() && auth()->user()->isAdmin())
        .admin-sidebar {
            width: var(--sidebar-width);
            background: #1e293b;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            color: white;
            border-right: 3px solid var(--primary-color);
            display: flex;
            flex-direction: column;
        }

        .admin-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        .admin-footer {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        .admin-navbar {
            display: none !important;
        }
        @else
        .admin-sidebar {
            display: none !important;
        }

        .admin-content {
            margin-left: 0;
            width: 100%;
        }

        .admin-footer {
            margin-left: 0;
            width: 100%;
        }

        .admin-navbar {
            display: flex !important;
        }
        @endif

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #334155;
            background: #0f172a;
            min-height: 70px;
            display: flex;
            align-items: center;
        }

        .sidebar-header h5 {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .sidebar-menu {
            list-style: none;
            flex-grow: 1;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu li {
            width: 100%;
        }

        .sidebar-menu a {
            display: block;
            padding: 14px 25px;
            color: #cbd5e1;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: #2d3748;
            border-left: 4px solid var(--primary-color);
            color: white;
        }

        .sidebar-menu a.active {
            background: #1e40af;
            border-left: 4px solid var(--secondary-color);
            color: white;
        }

        .sidebar-bottom {
            border-top: 1px solid #334155;
            padding: 20px;
            background: #0f172a;
        }

        .sidebar-bottom button {
            display: block;
            width: 100%;
            padding: 10px 0;
            color: #cbd5e1;
            text-decoration: none;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .sidebar-bottom button:hover {
            color: var(--secondary-color);
        }

        .admin-top-bar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 60px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .navbar-custom {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0.5rem 2rem;
            min-height: 70px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar-brand-container {
            background: var(--primary-color);
            color: white;
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 1.8rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            margin-right: auto;
            margin-left: 230px; 
        }

        .navbar-brand-container:hover {
            background: #2a002d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(56, 0, 60, 0.3);
        }

        .navbar-right-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-left: auto;
        }

        .nav-link-custom {
            color: #4b5563 !important;
            font-weight: 500;
            padding: 0.5rem 0.75rem !important;
            transition: color 0.2s;
        }

        .nav-link-custom:hover {
            color: var(--primary-color) !important;
        }

        .dropdown-toggle {
            border: none;
            background: none;
            color: #4b5563;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
        }

        .dropdown-toggle:hover {
            color: var(--primary-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 10px;
            padding: 0.5rem;
            min-width: 220px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin: 0.1rem 0;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.text-danger:hover {
            background: #fee2e2;
        }

        .cart-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            position: absolute;
            top: -5px;
            right: -8px;
        }

        .main-content {
            min-height: calc(100vh - 120px);
            padding: 2rem;
        }

        .footer-custom {
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
            color: #6b7280;
        }

        @if(auth()->check() && auth()->user()->isAdmin())
        .admin-content {
            margin-top: 60px;
            min-height: calc(100vh - 180px);
        }
        @endif
    </style>
</head>

<body>
    @if(auth()->check() && auth()->user()->isAdmin())
    <div class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h5>Admin Panel</h5>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </li>
            
            @if(Route::has('admin.index'))
            <li>
                <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    Home
                </a>
            </li>
            @endif
            
            <li>
                <a href="{{ url('/admin/users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    Users
                </a>
            </li>
            
            @if(Route::has('password.change'))
            <li>
                <a href="{{ route('password.change') }}" class="{{ request()->routeIs('password.change') ? 'active' : '' }}">
                    Settings
                </a>
            </li>
            @endif
            
            @if(Route::has('tickets.create'))
            <li>
                <a href="{{ route('tickets.create') }}" class="{{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                </a>
            </li>
            @endif
        </ul>

        <div class="sidebar-bottom">
            <div style="padding: 10px 0; color: #94a3b8; font-size: 14px;">
                {{ auth()->user()->name }}
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @else
    <nav class="navbar navbar-expand-lg navbar-custom admin-navbar">
        <div class="container-fluid px-0">
            <a class="navbar-brand-container" href="{{ route('user.dashboard') }}">
                TICKETSAPP
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarRightContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarRightContent">
                <div class="navbar-right-menu">
                    @if(Route::has('user.dashboard'))
                    <a class="nav-link-custom" href="{{ route('user.dashboard') }}">
                        Home
                    </a>
                    @endif

                    @if(auth()->check() && auth()->user()->isUser() && Route::has('cart.index'))
                    <div class="position-relative">
                        <a class="nav-link-custom" href="{{ route('cart.index') }}">
                            Cart
                            @php $cartCount = auth()->user()->cartCount(); @endphp
                            @if($cartCount > 0)
                            <span class="cart-badge badge bg-danger rounded-pill">
                                {{ $cartCount }}
                            </span>
                            @endif
                        </a>
                    </div>
                    @endif

                    @guest
                    @if(Route::has('login'))
                    <a class="nav-link-custom" href="{{ route('login') }}">
                        Sign in
                    </a>
                    @endif
                    @else
                    <div class="dropdown">
                        <a class="dropdown-toggle nav-link-custom" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Settings
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li class="dropdown-header text-center mb-2">
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @if(Route::has('password.change'))
                            <li>
                                <a class="dropdown-item" href="{{ route('password.change') }}">
                                    Change Password
                                </a>
                            </li>
                            @endif
                            
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    @endif

    <main class="main-content admin-content">
        <div class="content-card">
            @yield('content')
        </div>
    </main>

    <footer class="footer-custom admin-footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted">&copy; {{ date('Y') }} Tickets App. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted">v1.0.0</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const topBar = document.getElementById('adminTopBar');
        const mainContent = document.querySelector('.admin-content');
        const footer = document.querySelector('.admin-footer');
        
        if (sidebar.style.width === '0px' || sidebar.style.width === '') {
            sidebar.style.width = 'var(--sidebar-width)';
            topBar.style.left = 'var(--sidebar-width)';
            mainContent.style.marginLeft = 'var(--sidebar-width)';
            mainContent.style.width = 'calc(100% - var(--sidebar-width))';
            footer.style.marginLeft = 'var(--sidebar-width)';
            footer.style.width = 'calc(100% - var(--sidebar-width))';
        } else {
            sidebar.style.width = '0';
            topBar.style.left = '0';
            mainContent.style.marginLeft = '0';
            mainContent.style.width = '100%';
            footer.style.marginLeft = '0';
            footer.style.width = '100%';
        }
    }
    </script>
</body>
</html>