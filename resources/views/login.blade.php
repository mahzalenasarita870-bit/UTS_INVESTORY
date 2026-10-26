<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System - GlowDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-main: #fbf7f4;
            --bg-card: #ffffff;
            --btn-primary: #c69c82;
            --btn-hover: #b58b71;
            --text-dark: #3a2e2b;
            --border-color: #f0e6df;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            background: var(--bg-card);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(58, 46, 43, 0.05);
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            background: #d9bfb0;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 24px;
            margin: 0 auto 15px auto;
        }

        .form-control {
            background-color: #fbf7f4;
            border: 1px solid #e8ded8;
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text-dark);
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--btn-primary);
            box-shadow: 0 0 0 0.25rem rgba(198, 156, 130, 0.25);
            color: var(--text-dark);
        }

        .btn-glow {
            background-color: var(--btn-primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-glow:hover {
            background-color: var(--btn-hover);
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-icon">
            <i class="bi bi-flower1"></i>
        </div>
        <h4 class="fw-bold mb-1">Login System</h4>
        <small class="text-muted">GlowDesk - Skincare Inventory & POS</small>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4 small">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-muted">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@mahzaglow.com">
        </div>

        <div class="mb-4">
            <label for="password" class="form-label small fw-semibold text-muted">Password</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-glow w-100">
            Login
        </button>
    </form>
</div>

</body>
</html>