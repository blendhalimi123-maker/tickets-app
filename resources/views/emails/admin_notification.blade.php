<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #eee; padding: 20px; }
        .header { background: #2d3748; color: white; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Sale Alert!</h1>
        </div>
        <p>Hello Admin,</p>
        <p>A new purchase has been made by <strong>{{ $user->name }}</strong> ({{ $user->email }}).</p>
        
        <h3>Order Summary:</h3>
        <ul>
            @foreach($tickets as $ticket)
                <li>{{ $ticket->event_name }} - {{ $ticket->ticket_type }} (Quantity: {{ $ticket->quantity ?? 1 }})</li>
            @endforeach
        </ul>
        
        <p>Log in to your dashboard to view full transaction details.</p>
    </div>
</body>
</html>