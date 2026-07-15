<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KKN-T IDBU 50 ROWOSARI Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,400;7..72,700;7..72,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-card__logo" style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Rowosari 3R" style="height: 120px; width: auto; object-fit: contain;">
                <h2>KKN-T IDBU 50 ROWOSARI</h2>
                <p style="margin-top: -8px;">Masuk ke Admin Panel</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <span class="material-symbols-outlined">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input"
                           value="{{ old('email') }}" required autofocus
                           placeholder="admin@rowosari3r.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                           required placeholder="Masukkan password">
                </div>

                <div class="form-group">
                    <label class="form-checkbox">
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
                    <span class="material-symbols-outlined">login</span>
                    Masuk
                </button>
            </form>

            <div style="text-align:center;margin-top:var(--space-xl);">
                <a href="{{ route('home') }}" style="color:var(--color-muted);font-size:0.9rem;">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
