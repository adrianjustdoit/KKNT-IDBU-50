<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Program pengelolaan sampah 3R (Reduce, Reuse, Recycle) oleh KKN Undip di Kelurahan Rowosari, Tembalang. Produk daur ulang & ramah lingkungan.">
    <meta name="keywords" content="KKN Rowosari, 3R, Reduce Reuse Recycle, daur ulang, Semarang, Tembalang, produk ramah lingkungan">
    <title>@yield('title', 'KKN-T IDBU 50 ROWOSARI — Pengelolaan Sampah Berkelanjutan')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,700;0,7..72,800;1,7..72,400&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Navbar --}}
    <div class="navbar-overlay" id="navOverlay"></div>
    <nav class="navbar {{ request()->routeIs('home', 'struktur') ? 'navbar--transparent is-transparent' : 'navbar--solid' }}" id="navbar">
        <div class="navbar__inner">
            <a href="{{ route('home') }}" class="navbar__brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Rowosari 3R" style="height: 54px; width: auto; object-fit: contain; margin-right: 4px;">
                KKN-T IDBU 50 ROWOSARI
            </a>

            <button class="navbar__toggle" id="navToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="navbar__nav" id="navMenu">
                <li><a href="{{ request()->routeIs('home') ? '#hero' : route('home') . '#hero' }}" class="navbar__link">Beranda</a></li>
                <li><a href="{{ request()->routeIs('home') ? '#tentang' : route('home') . '#tentang' }}" class="navbar__link">Tentang</a></li>
                <li><a href="{{ route('struktur') }}" class="navbar__link {{ request()->routeIs('struktur') ? 'active' : '' }}">Struktur Organisasi</a></li>
                <li><a href="{{ request()->routeIs('home') ? '#galeri' : route('home') . '#galeri' }}" class="navbar__link">Galeri</a></li>
                <li><a href="{{ request()->routeIs('home') ? '#katalog' : route('home') . '#katalog' }}" class="navbar__link">Katalog</a></li>
                <li><a href="{{ request()->routeIs('home') ? '#kontak' : route('home') . '#kontak' }}" class="navbar__link">Kontak</a></li>
                @auth
                    <li><a href="{{ route('admin.dashboard') }}" class="navbar__link navbar__link--cta">Admin Panel</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    {{-- Main Content --}}
    @yield('content')

    {{-- Product Detail Modal --}}
    <div class="modal-overlay" id="productModal">
        <div class="modal">
            <button class="modal__close" aria-label="Close">
                <span class="material-symbols-outlined">close</span>
            </button>
            <img class="modal__image" src="" alt="">
            <div class="modal__gallery"></div>
            <div class="modal__body">
                <span class="modal__category"></span>
                <h3 class="modal__title"></h3>
                <div class="modal__price"></div>
                <p class="modal__desc"></p>
                <div class="modal__links">
                    <a href="{{ route('kompos.eksplorasi') }}" class="btn btn-primary modal__kompos" target="_self" style="display:none; background: linear-gradient(135deg, var(--color-forest), var(--color-amber));">
                        <span class="material-symbols-outlined">layers</span>
                        Eksplorasi Kompos
                    </a>
                    <a href="#" class="btn btn-primary modal__view3d" target="_self" style="display:none;">
                        <span class="material-symbols-outlined">view_in_ar</span>
                        Lihat Model 3D
                    </a>
                    <a href="#" class="btn btn-primary modal__shopee" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        Beli di Shopee
                    </a>
                    <a href="#" class="btn btn-amber modal__tokopedia" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">storefront</span>
                        Beli di Tokopedia
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div class="lightbox" id="lightbox">
        <button class="lightbox__close" aria-label="Close">
            <span class="material-symbols-outlined">close</span>
        </button>
        <img class="lightbox__img" src="" alt="">
    </div>

    {{-- Floating WhatsApp Contact --}}
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', App\Models\SiteSetting::get('contact_phone', '6281234567890')) }}" 
       class="floating-contact" target="_blank" rel="noopener noreferrer" aria-label="Hubungi kami via WhatsApp">
        <span class="floating-contact__label">Chat Kami</span>
        <div class="floating-contact__btn">
            <svg class="wa-icon" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            </svg>
        </div>
    </a>

    {{-- Scroll to Top Button --}}
    <div class="scroll-top" aria-label="Kembali ke atas">
        <svg class="scroll-top__ring" width="48" height="48" viewBox="0 0 48 48">
            <circle class="ring-bg" cx="24" cy="24" r="20" />
            <circle class="ring-progress" cx="24" cy="24" r="20" />
        </svg>
        <button class="scroll-top__btn">
            <span class="material-symbols-outlined">arrow_upward</span>
        </button>
    </div>
</body>
</html>
