<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-r from-purple-50 via-pink-50 to-yellow-50 font-sans text-gray-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('tickets.index') }}" class="text-2xl font-bold hover:text-yellow-200 transition">
                TicketsApp
            </a>

            <div class="flex items-center space-x-4">
                <a href="{{ route('tickets.index') }}" class="font-semibold hover:text-yellow-200 transition">Home</a>

                <!-- Admin-only: Create Ticket -->
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('tickets.create') }}" class="font-semibold hover:text-yellow-200 transition">
                        Create Ticket
                    </a>
                @endif

                <!-- User-only: Shopping Cart -->
                @if(auth()->check() && auth()->user()->isUser())
                    <a href="{{ route('cart.index') }}" class="relative font-semibold hover:text-yellow-200 transition">
                        🛒 Cart
                        @php
                            $cartCount = auth()->user()->cartCount();
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full px-1">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Auth links -->
                @guest
                    <a href="{{ route('login') }}" class="font-semibold hover:text-yellow-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="font-semibold hover:text-yellow-200 transition">Register</a>
                @else
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold hover:text-yellow-200 transition">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 py-6">
        <div class="bg-white shadow rounded-lg p-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-pink-200 via-purple-300 to-blue-200 py-4 text-center text-gray-800 mt-6">
        &copy; {{ date('Y') }} Tickets App. All rights reserved.
    </footer>

</body>
</html>
