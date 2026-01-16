<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #eee; padding: 20px; }
        .header { background: #4a5568; color: white; padding: 10px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f8f8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thanks for your purchase!</h1>
        </div>
        <p>Hi {{ $user->name }},</p>
        <p>Your tickets are confirmed. Here are your booking details:</p>

        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Ticket Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->event_name }}</td>
                    <td>{{ $ticket->ticket_type }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Please keep this email for your records. We look forward to seeing you at the event!</p>
    </div>
</body>
</html>