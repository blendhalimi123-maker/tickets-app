<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets App</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; }
        nav a:hover { text-decoration: underline; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>

    <nav>
        <a href="{{ route('tickets.index') }}">Home</a>
        <a href="{{ route('tickets.create') }}">Create Ticket</a>
    </nav>

    <hr>

    <!-- Main content -->
    @yield('content')

</body>
</html>
