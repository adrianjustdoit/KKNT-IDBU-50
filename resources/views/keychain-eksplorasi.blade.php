<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Eksplorasi Keychain Eco 3R — scroll untuk melihat proses produksi gantungan kunci daur ulang berlapis hingga exploded view.">
    <title>Eksplorasi Keychain Eco 3R — KKN-T IDBU 50 ROWOSARI</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,700;0,7..72,800;1,7..72,400&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/scroll-sequence.js'])
</head>
<body class="kompos-page">

    {{-- =================== LOADING SCREEN =================== --}}
    <div class="kompos-loader" id="komposLoader">
        <div class="kompos-loader__inner">
            <div class="kompos-loader__icon">
                <span class="material-symbols-outlined">key</span>
            </div>
            <h2 class="kompos-loader__title">Memuat Eksplorasi Keychain</h2>
            <p class="kompos-loader__subtitle">Menyiapkan 4 lapisan Keychain Eco 3R untuk dijelajahi...</p>
            <div class="kompos-loader__bar">
                <div class="kompos-loader__fill" id="loadProgress"></div>
            </div>
            <span class="kompos-loader__percent" id="loadPercent">0%</span>
        </div>
    </div>

    {{-- =================== BACK NAVIGATION =================== --}}
    <a href="{{ route('home') }}#katalog" class="kompos-back" aria-label="Kembali ke katalog" id="komposBack">
        <span class="material-symbols-outlined">arrow_back</span>
        <span class="kompos-back__text">Katalog</span>
    </a>

    {{-- =================== HEADER OVERLAY =================== --}}
    <div class="kompos-header" id="komposHeader">
        <span class="kompos-header__badge">
            <span class="material-symbols-outlined">layers</span>
            Scroll Exploration
        </span>
        <h1 class="kompos-header__title">Eksplorasi Keychain Eco 3R</h1>
        <p class="kompos-header__subtitle">Scroll perlahan untuk melihat 4 lapisan penyusun gantungan kunci daur ulang</p>
        <div class="kompos-header__scroll-hint" id="scrollHint">
            <span class="material-symbols-outlined kompos-header__scroll-icon">expand_more</span>
            <span>Scroll ke bawah</span>
        </div>
    </div>

    {{-- =================== SCROLL PROGRESS =================== --}}
    <div class="kompos-progress" id="komposProgress">
        <div class="kompos-progress__track">
            <div class="kompos-progress__fill" id="progressFill"></div>
        </div>
        <span class="kompos-progress__label" id="progressLabel">0%</span>
    </div>

    {{-- =================== SCROLL CONTAINER =================== --}}
    <div class="kompos-scroll" id="komposScroll">
        {{-- Canvas for frame rendering --}}
        <canvas class="kompos-canvas" id="komposCanvas"></canvas>

        {{-- Static fallback --}}
        <img class="kompos-fallback" id="komposFallback"
             src="{{ asset('images/keychain-frames/frame-100.png') }}"
             alt="Exploded view Keychain Eco 3R">

        {{-- Scroll spacer --}}
        <div class="kompos-spacer"></div>

        {{-- =================== LAYER CALLOUTS =================== --}}
        <div class="kompos-callouts" id="komposCallouts">
            <div class="kompos-callout" data-layer="1">
                <div class="kompos-callout__number">1</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">Ring & Rantai Logam</h3>
                    <p class="kompos-callout__desc">Pengait stainless steel kokoh dengan daya tahan benturan tinggi.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="2">
                <div class="kompos-callout__number">2</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">Matriks Resin Bening</h3>
                    <p class="kompos-callout__desc">Proteksi transparan pembungkus ornamen limbah secara presisi.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="3">
                <div class="kompos-callout__number">3</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">Potongan Kain & Plastik</h3>
                    <p class="kompos-callout__desc">Ornamen limbah warna-warni terkurasi berestetika visual unik.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="4">
                <div class="kompos-callout__number">4</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">Simbol 3R Eco-Pendant</h3>
                    <p class="kompos-callout__desc">Pesan ikonik kampanye keberlanjutan lingkungan hidup.</p>
                </div>
            </div>
        </div>

        {{-- Watermark Cover Badge --}}
        <div class="kompos-emblem" aria-hidden="true">
            <div class="kompos-emblem__inner">
                <span class="material-symbols-outlined kompos-emblem__icon">eco</span>
                <div class="kompos-emblem__text">
                    <span class="kompos-emblem__title">ROWOSARI 3R</span>
                    <span class="kompos-emblem__sub">ECO SYSTEM</span>
                </div>
            </div>
        </div>
    </div>

    {{-- =================== BOTTOM CTA =================== --}}
    <div class="kompos-bottom" id="komposBottom">
        <div class="kompos-bottom__inner">
            <div class="kompos-bottom__info">
                <span class="material-symbols-outlined kompos-bottom__icon">key</span>
                <div>
                    <h3>Aksesoris Eco-Living Unik</h3>
                    <p>Setiap gantungan kunci dibuat secara *handmade* dari limbah terolah di Kelurahan Rowosari.</p>
                </div>
            </div>
            <a href="{{ route('home') }}#katalog" class="kompos-bottom__btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali ke Katalog
            </a>
        </div>
    </div>

    {{-- Pass data to JS --}}
    <script>
        window.seqConfig = {
            totalFrames: {{ $totalFrames }},
            framePath: '{{ asset("images/keychain-frames") }}',
            prefix: 'kompos'
        };
    </script>
</body>
</html>
