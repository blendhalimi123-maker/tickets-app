<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fce7f3, #e0e7ff);
        }
        .card {
            border-radius: 1rem;
        }
        .btn-primary {
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
        }
        .code-input {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 10px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-sm p-5" style="width: 100%; max-width: 420px;">
    <h2 class="text-center mb-3 fw-bold">Enter Code</h2>
    <p class="text-center text-muted mb-4">We sent a 6-digit code to <strong>{{ $email }}</strong></p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.verify.code') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <input type="text" name="code" class="form-control form-control-lg code-input @error('code') is-invalid @enderror" maxlength="6" placeholder="000000" value="{{ old('code') }}" required autofocus>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">Verify Code</button>
        </div>

        <p class="text-center text-muted mb-0">
            Didn't receive the code?
            <a href="{{ route('password.request') }}" class="text-primary fw-bold">Resend</a>
        </p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
