<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f0f2f5; color: #1a1a2e; line-height: 1.6; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        .wrapper { max-width: 640px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); padding: 40px 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 6px; }
        .header p { color: #a5b4fc; font-size: 14px; font-weight: 400; }
        .body { padding: 32px; }
        .badge { display: inline-block; background: #fee2e2; color: #dc2626; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .buyer-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .buyer-card .label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: 600; margin-bottom: 4px; }
        .buyer-card .value { font-size: 16px; font-weight: 600; color: #1e293b; }
        .buyer-card .email { font-size: 14px; color: #6366f1; text-decoration: none; word-break: break-all; }
        .section-title { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: 700; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; }
        .ticket-item { background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 20px; margin-bottom: 12px; }
        .ticket-item:last-child { margin-bottom: 0; }
        .ticket-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .ticket-match { font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 2px; }
        .ticket-details { font-size: 13px; color: #64748b; line-height: 1.5; }
        .ticket-qty { background: #6366f1; color: #ffffff; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 20px; white-space: nowrap; display: inline-block; margin-top: 4px; flex-shrink: 0; }
        .total-bar { background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 12px; padding: 20px 24px; margin-top: 24px; }
        .total-row { display: flex; justify-content: space-between; align-items: center; }
        .total-bar .total-label { color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 500; }
        .total-bar .total-amount { color: #ffffff; font-size: 24px; font-weight: 700; }
        .footer { padding: 24px 32px; text-align: center; border-top: 1px solid #f1f5f9; }
        .footer p { font-size: 13px; color: #94a3b8; }
        .footer a { color: #6366f1; text-decoration: none; font-weight: 600; }

        @media only screen and (max-width: 480px) {
            .wrapper { margin: 0; border-radius: 0; width: 100% !important; }
            .header { padding: 28px 20px; }
            .header h1 { font-size: 22px; }
            .body { padding: 20px 16px; }
            .buyer-card { padding: 16px; }
            .buyer-card .value { font-size: 15px; }
            .ticket-item { padding: 14px 16px; }
            .ticket-row { flex-direction: column; gap: 8px; }
            .ticket-match { font-size: 14px; }
            .ticket-details { font-size: 12px; }
            .ticket-qty { align-self: flex-start; }
            .total-bar { padding: 16px 18px; }
            .total-bar .total-amount { font-size: 20px; }
            .footer { padding: 20px 16px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>New Sale Alert</h1>
            <p>{{ now()->format('D, d M Y \a\t H:i') }}</p>
        </div>
        <div class="body">
            <span class="badge">New Order</span>

            <div class="buyer-card">
                <div class="label">Purchased by</div>
                <div class="value">{{ $user->name }}</div>
                <a class="email" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
            </div>

            <div class="section-title">Order Items</div>

            @php $orderTotal = 0; @endphp
            @foreach($tickets as $ticket)
                @php $orderTotal += $ticket->price * ($ticket->quantity ?? 1); @endphp
                <div class="ticket-item">
                    <div class="ticket-row">
                        <div>
                            <div class="ticket-match">{{ $ticket->home_team }} vs {{ $ticket->away_team }}</div>
                            <div class="ticket-details">
                                {{ $ticket->stadium }}<br>
                                {{ $ticket->stand }} / Row {{ $ticket->row }} / Seat {{ $ticket->seat_number }} &middot; {{ $ticket->category }}
                            </div>
                        </div>
                        <span class="ticket-qty">× {{ $ticket->quantity ?? 1 }}</span>
                    </div>
                </div>
            @endforeach

            <div class="total-bar">
                <div class="total-row">
                    <span class="total-label">Order Total</span>
                    <span class="total-amount">£{{ number_format($orderTotal, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="footer">
            <p><a href="{{ url('/admin') }}">Open Dashboard</a> to view full transaction details.</p>
        </div>
    </div>
</body>
</html>