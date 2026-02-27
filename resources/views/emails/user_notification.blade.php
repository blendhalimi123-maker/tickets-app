<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f0f2f5; color: #1a1a2e; line-height: 1.6; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        .wrapper { max-width: 640px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); padding: 48px 32px; text-align: center; }
        .header .icon { font-size: 48px; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 6px; }
        .header p { color: #a5b4fc; font-size: 14px; }
        .body { padding: 32px; }
        .greeting { font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
        .subtext { font-size: 14px; color: #64748b; margin-bottom: 28px; }
        .section-title { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: 700; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; }
        .ticket-card { background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        .ticket-card:last-of-type { margin-bottom: 0; }
        .match-title { font-size: 17px; font-weight: 700; color: #1e293b; margin-bottom: 12px; }
        .info-grid { display: flex; flex-wrap: wrap; gap: 12px; }
        .info-item { flex: 1 1 45%; min-width: 140px; }
        .info-item .label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; font-weight: 600; margin-bottom: 2px; }
        .info-item .value { font-size: 14px; font-weight: 600; color: #334155; word-break: break-word; }
        .price-tag { display: inline-block; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; font-size: 16px; font-weight: 700; padding: 8px 20px; border-radius: 24px; margin-top: 14px; }
        .summary-bar { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-top: 28px; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 14px; color: #64748b; }
        .summary-row.total { border-top: 2px solid #e2e8f0; margin-top: 8px; padding-top: 14px; font-size: 18px; font-weight: 700; color: #1e293b; }
        .notice { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 16px 20px; margin-top: 28px; font-size: 13px; color: #1e40af; text-align: center; }
        .footer { padding: 28px 32px; text-align: center; border-top: 1px solid #f1f5f9; }
        .footer p { font-size: 13px; color: #94a3b8; margin-bottom: 4px; }
        .footer a { color: #6366f1; text-decoration: none; font-weight: 600; }

        @media only screen and (max-width: 480px) {
            .wrapper { margin: 0; border-radius: 0; width: 100% !important; }
            .header { padding: 32px 20px; }
            .header .icon { font-size: 36px; }
            .header h1 { font-size: 22px; }
            .body { padding: 20px 16px; }
            .greeting { font-size: 16px; }
            .subtext { font-size: 13px; margin-bottom: 20px; }
            .ticket-card { padding: 16px; }
            .match-title { font-size: 15px; margin-bottom: 10px; }
            .info-grid { gap: 10px; }
            .info-item { flex: 1 1 100%; min-width: 0; }
            .info-item .value { font-size: 13px; }
            .price-tag { font-size: 14px; padding: 6px 16px; }
            .summary-bar { padding: 16px 18px; }
            .summary-row { font-size: 13px; }
            .summary-row.total { font-size: 16px; }
            .notice { padding: 14px 16px; font-size: 12px; }
            .footer { padding: 20px 16px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="icon">🎟️</div>
            <h1>Booking Confirmed!</h1>
            <p>Your tickets are ready</p>
        </div>
        <div class="body">
            <p class="greeting">Hi {{ $user->name }},</p>
            <p class="subtext">Thanks for your purchase! Here are your booking details.</p>

            <div class="section-title">Your Tickets</div>

            @php $orderTotal = 0; @endphp
            @foreach($tickets as $ticket)
                @php $lineTotal = $ticket->price * ($ticket->quantity ?? 1); $orderTotal += $lineTotal; @endphp
                <div class="ticket-card">
                    <div class="match-title">{{ $ticket->home_team }} vs {{ $ticket->away_team }}</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">Date & Time</div>
                            <div class="value">{{ \Carbon\Carbon::parse($ticket->match_date)->format('D d M Y, H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Stadium</div>
                            <div class="value">{{ $ticket->stadium }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Seat</div>
                            <div class="value">{{ $ticket->stand }} / Row {{ $ticket->row }} / Seat {{ $ticket->seat_number }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Category</div>
                            <div class="value">{{ $ticket->category }}</div>
                        </div>
                    </div>
                    <span class="price-tag">£{{ number_format($lineTotal, 2) }}</span>
                </div>
            @endforeach

            <div class="summary-bar">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>£{{ number_format($orderTotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Service Fee</span>
                    <span>£{{ number_format(count($tickets) * 2.50, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total Paid</span>
                    <span>£{{ number_format($orderTotal + count($tickets) * 2.50, 2) }}</span>
                </div>
            </div>

            <div class="notice">
                📌 Please save this email as your booking confirmation. Present it at the venue entrance.
            </div>
        </div>
        <div class="footer">
            <p>We look forward to seeing you at the match!</p>
            <p><a href="{{ url('/') }}">Visit Tickets App</a></p>
        </div>
    </div>
</body>
</html>