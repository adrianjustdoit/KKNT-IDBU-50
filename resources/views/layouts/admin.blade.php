<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — KKN-T IDBU 50 ROWOSARI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,400;7..72,700;7..72,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="admin-layout">
        {{-- Sidebar --}}
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar__brand">
                <h3 style="display: flex; align-items: center; gap: 8px;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Rowosari 3R" style="height: 44px; width: auto; object-fit: contain;">
                    KKN-T IDBU 50 ROWOSARI
                </h3>
                <small>Admin Panel</small>
            </div>

            <ul class="admin-sidebar__nav">
                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        Dashboard
                    </a>
                </li>
                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.products.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">inventory_2</span>
                        Katalog Produk
                    </a>
                </li>
                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.members.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">groups</span>
                        Struktur Organisasi
                    </a>
                </li>

                <div class="admin-sidebar__divider"></div>

                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.media.video') }}" class="admin-sidebar__link {{ request()->routeIs('admin.media.video*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">videocam</span>
                        Video
                    </a>
                </li>
                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.news.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined admin-sidebar__icon">newspaper</span>
                        Berita Harian
                    </a>
                </li>
                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.media.gallery') }}" class="admin-sidebar__link {{ request()->routeIs('admin.media.gallery') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">photo_library</span>
                        Galeri Foto
                    </a>
                </li>

                <div class="admin-sidebar__divider"></div>

                <li class="admin-sidebar__item">
                    <a href="{{ route('admin.settings') }}" class="admin-sidebar__link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">settings</span>
                        Pengaturan
                    </a>
                </li>
                <li class="admin-sidebar__item">
                    <a href="{{ route('home') }}" class="admin-sidebar__link" target="_blank">
                        <span class="material-symbols-outlined">open_in_new</span>
                        Lihat Website
                    </a>
                </li>

                <div class="admin-sidebar__divider"></div>

                <li class="admin-sidebar__item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-sidebar__link" style="width:100%;border:none;background:none;cursor:pointer;text-align:left;font-family:inherit;font-size:inherit;">
                            <span class="material-symbols-outlined">logout</span>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        {{-- Main Content --}}
        <main class="admin-main">
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
    </div>
</body>
</html>
