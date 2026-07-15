<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Eksplorasi 7 lapisan pupuk kompos Rowosari — scroll untuk melihat proses produksi kompos berlapis dari wadah utuh hingga exploded view.">
    <title>Eksplorasi Pupuk Kompos — KKN-T IDBU 50 ROWOSARI</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,700;0,7..72,800;1,7..72,400&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/kompos-eksplorasi.js'])
</head>
<body class="kompos-page">

    {{-- =================== LOADING SCREEN =================== --}}
    <div class="kompos-loader" id="komposLoader">
        <div class="kompos-loader__inner">
            <div class="kompos-loader__icon">
                <span class="material-symbols-outlined">compost</span>
            </div>
            <h2 class="kompos-loader__title">Memuat Eksplorasi Kompos</h2>
            <p class="kompos-loader__subtitle">Menyiapkan 7 lapisan kompos untuk dijelajahi...</p>
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

    {{-- =================== HEADER OVERLAY (top of page) =================== --}}
    <div class="kompos-header" id="komposHeader">
        <span class="kompos-header__badge">
            <span class="material-symbols-outlined">layers</span>
            Scroll Exploration
        </span>
        <h1 class="kompos-header__title">Eksplorasi Pupuk Kompos</h1>
        <p class="kompos-header__subtitle">Scroll perlahan untuk melihat 7 lapisan kompos yang membentuk pupuk organik berkualitas tinggi</p>
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

        {{-- Static fallback for prefers-reduced-motion --}}
        <img class="kompos-fallback" id="komposFallback"
             src="{{ asset('images/kompos-frames/frame-100.png') }}"
             alt="Exploded view pupuk kompos 7 lapisan">

        {{-- Scroll spacer to create scroll distance --}}
        <div class="kompos-spacer"></div>

        {{-- =================== LAYER CALLOUTS =================== --}}
        <div class="kompos-callouts" id="komposCallouts">
            <div class="kompos-callout" data-layer="1">
                <div class="kompos-callout__number">1</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">🌾 Sekam</h3>
                    <p class="kompos-callout__desc">Menjaga sirkulasi udara di dalam wadah kompos, mempercepat proses dekomposisi secara aerobik.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="2">
                <div class="kompos-callout__number">2</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">🪨 Granit / Kerikil</h3>
                    <p class="kompos-callout__desc">Berfungsi sebagai drainase dan filter alami, mencegah genangan air yang bisa menghambat pengomposan.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="3">
                <div class="kompos-callout__number">3</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">💧 Bioaqua</h3>
                    <p class="kompos-callout__desc">Aktivator mikroorganisme pengurai bahan organik, mempercepat proses fermentasi dan dekomposisi.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="4">
                <div class="kompos-callout__number">4</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">🥬 Sampah Sayur</h3>
                    <p class="kompos-callout__desc">Sumber nitrogen utama untuk nutrisi kompos, memberikan bahan organik kaya nutrisi bagi tanaman.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="5">
                <div class="kompos-callout__number">5</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">🍂 Daun Kering</h3>
                    <p class="kompos-callout__desc">Sumber karbon penting yang menyeimbangkan rasio C/N, memastikan pengomposan berjalan optimal.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="6">
                <div class="kompos-callout__number">6</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">🐐 Kotoran Kambing</h3>
                    <p class="kompos-callout__desc">Memperkaya nutrisi kompos dan mempercepat proses fermentasi berkat kandungan bakteri alami.</p>
                </div>
            </div>

            <div class="kompos-callout" data-layer="7">
                <div class="kompos-callout__number">7</div>
                <div class="kompos-callout__content">
                    <h3 class="kompos-callout__name">🟤 Tanah</h3>
                    <p class="kompos-callout__desc">Lapisan dasar yang menyediakan bakteri pengurai alami dan fondasi struktur kompos keseluruhan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- =================== BOTTOM CTA =================== --}}
    <div class="kompos-bottom" id="komposBottom">
        <div class="kompos-bottom__inner">
            <div class="kompos-bottom__info">
                <span class="material-symbols-outlined kompos-bottom__icon">eco</span>
                <div>
                    <h3>100% Bahan Organik & Alami</h3>
                    <p>Setiap lapisan dipilih dengan cermat untuk menghasilkan pupuk kompos berkualitas tinggi yang ramah lingkungan.</p>
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
        window.komposConfig = {
            totalFrames: {{ $totalFrames }},
            framePath: '{{ asset("images/kompos-frames") }}',
        };
    </script>
</body>
</html>
