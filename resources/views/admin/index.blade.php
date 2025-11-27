<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4">Welcome Admin, {{ auth()->user()->name }}</h1>
    <p class="mb-6">Manage tickets here.</p>

    <!-- Buttons -->
    <div class="space-y-4">
        <!-- Manage Tickets -->
        <a href="{{ route('tickets.index') }}" 
           class="block bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
            Manage Tickets
        </a>

        <!-- Placeholder for future features (users etc.) -->
        {{-- <a href="{{ route('admin.users') }}" 
           class="block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">
            Manage Users
        </a> --}}
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">
            Logout
        </button>
    </form>
</div>

</body>
</html>
