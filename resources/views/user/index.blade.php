<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Welcome {{ auth()->user()->name }}</h1>
        <p class="mb-6">View and buy tickets here.</p>

        <a href="{{ route('tickets.index') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Tickets</a>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">
                Logout
            </button>
        </form>
    </div>

</body>
</html>
