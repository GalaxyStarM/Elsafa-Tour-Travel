<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Elsafa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh;
            background: #f0f2f5;
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%; max-width: 420px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
        }
        .login-logo {
            width: 250px;
            height: 250px;
            object-fit: contain;
            display: block;
            margin: 0 auto 16px;
        }
        .input-group-text { background: #f8f9fa; border-right: 0; color: #adb5bd; }
        .form-control { border-left: 0; }
        .form-control:focus { box-shadow: none; border-color: #ced4da; }
        .btn-login {
            background: #1a2b4a; color: #fff; border: none;
            padding: 12px; font-weight: 700; font-size: 15px;
            border-radius: 8px; width: 100%;
        }
        .btn-login:hover { background: #253d66; color: #fff; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <img src="{{ asset('images/elsafalogo.jpeg') }}"
             alt="Elsafa Logo"
             class="login-logo">
    </div>

    <p class="text-center fw-700 mb-1" style="font-size:20px;color:#1a2b4a;">Silahkan Login</p>
    <p class="text-center text-muted mb-4" style="font-size:13px;">Gunakan username dan password anda</p>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px;">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       placeholder="Username"
                       value="{{ old('username') }}" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password"
                       class="form-control"
                       placeholder="Password" required>
            </div>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>