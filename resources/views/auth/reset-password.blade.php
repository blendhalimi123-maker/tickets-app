<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-sm p-5" style="width: 100%; max-width: 420px;">
    <h2 class="text-center mb-3 fw-bold">New Password</h2>
    <p class="text-center text-muted mb-4">Enter your new password below.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="New Password" required autofocus>
        </div>

        <div class="mb-3">
            <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm Password" required>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
        </div>

        <p class="text-center text-muted mb-0">
            <a href="{{ route('login') }}" class="text-primary fw-bold">Back to Login</a>
        </p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
