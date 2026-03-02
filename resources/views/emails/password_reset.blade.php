<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f9fafb; color: #333; line-height: 1.7; -webkit-text-size-adjust: 100%; }
        .wrapper { max-width: 500px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; }
        .body { padding: 40px 32px; text-align: center; }
        .title { font-size: 20px; font-weight: 700; color: #111; margin-bottom: 8px; }
        .subtext { font-size: 14px; color: #666; margin-bottom: 32px; }
        .code { font-size: 34px; font-weight: 800; color: #111; letter-spacing: 10px; font-family: 'Courier New', monospace; background: #f3f4f6; border-radius: 10px; padding: 20px; margin-bottom: 28px; display: inline-block; }
        .expire { font-size: 13px; color: #999; margin-bottom: 24px; }
        .muted { font-size: 12px; color: #bbb; }
        .footer { padding: 20px 32px; text-align: center; }
        .footer a { font-size: 12px; color: #999; text-decoration: none; }

        @media only screen and (max-width: 480px) {
            .wrapper { margin: 0; border-radius: 0; width: 100% !important; }
            .body { padding: 32px 20px; }
            .code { font-size: 26px; letter-spacing: 6px; padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="body">
            <p class="title">Reset Your Password</p>
            <p class="subtext">Hi {{ $userName }}, use this code to reset your password.</p>
            <div class="code">{{ $code }}</div>
            <p class="expire">Expires in 15 minutes</p>
            <p class="muted">If you didn't request this, just ignore this email.</p>
        </div>
        <div class="footer">
            <a href="{{ url('/') }}">Tickets App</a>
        </div>
    </div>
</body>
</html>
