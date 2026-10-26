<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MahzaGlow POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #0f172a; font-family: 'Segoe UI', Roboto, sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .login-card { width: 100%; max-width: 420px; border: none; border-radius: 16px; background: #ffffff; padding: 30px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .btn-primary-custom { background-color: #2563eb; color: white; font-weight: 600; padding: 10px; border-radius: 8px; border: none; }
        .btn-primary-custom:hover { background-color: #1d4ed8; }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-dark mb-1">MahzaGlow</h2>
        <p class="text-muted small">Skincare Inventory Management</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3 text-center">
            <small>{{ $errors->first() }}</small>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label text-uppercase fw-bold text-muted small">USERNAME / EMAIL</label>
            <input type="email" name="email" class="form-control py-2" placeholder="admin@mahzaglow.com" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label text-uppercase fw-bold text-muted small">PASSWORD</label>
            <input type="password" name="password" class="form-control py-2" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 mb-3">Sign In</button>
    </form>

    <div class="text-center text-muted small mt-2">
        <strong>Akun Pengujian:</strong><br>
        Admin: admin@mahzaglow.com | Staff: staff@mahzaglow.com<br>
        Password: password
    </div>
</div>

</body>
</html>