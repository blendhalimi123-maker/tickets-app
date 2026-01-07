<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

<div class="card shadow-sm p-5" style="width: 100%; max-width: 400px;">
    <h2 class="text-center mb-4 fw-bold">Welcome Back!</h2>
    <p class="text-center text-muted mb-4">Log in to continue and explore amazing events.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-3">
            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>

        <p class="text-center text-muted mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold">Register</a>
        </p>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
