<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ruang Penulis') — KKN-T IDBU 50 ROWOSARI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,400;7..72,700;7..72,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Styles for Author Layout */
        body {
            background-color: var(--color-cream-light);
            font-family: var(--font-body);
        }
        
        .author-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(74, 124, 89, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: var(--space-md) var(--space-xl);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .author-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-heading);
            font-size: 1.25rem;
            color: var(--color-forest-dark);
            font-weight: 700;
            text-decoration: none;
        }

        .author-brand img {
            height: 36px;
            width: auto;
        }

        .author-nav-links {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .author-nav-link {
            color: var(--color-slate);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-full);
            transition: all var(--transition-fast);
        }

        .author-nav-link:hover, .author-nav-link.active {
            color: var(--color-forest);
            background: var(--color-forest-50);
        }

        .author-main {
            max-width: 1000px; /* Wider for comfortable reading/writing but not full width */
            margin: var(--space-2xl) auto;
            padding: 0 var(--space-xl);
        }

        /* Specific overrides for admin classes when used in author layout */
        .admin-header {
            margin-bottom: var(--space-2xl);
            padding-bottom: var(--space-md);
            border-bottom: 2px solid var(--color-light-gray);
        }
    </style>
</head>
<body>
    <nav class="author-navbar">
        <a href="{{ route('author.news.index') }}" class="author-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <span>Ruang Penulis</span>
        </a>

        <div class="author-nav-links">
            <a href="{{ route('author.news.index') }}" class="author-nav-link {{ request()->routeIs('author.news.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">edit_document</span>
                Draft Berita
            </a>
            <a href="{{ route('home') }}" target="_blank" class="author-nav-link">
                <span class="material-symbols-outlined">public</span>
                Lihat Website
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="author-nav-link" style="background:none;border:none;cursor:pointer;font-family:inherit;">
                    <span class="material-symbols-outlined">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <main class="author-main">
        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <span class="material-symbols-outlined">error</span>
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
