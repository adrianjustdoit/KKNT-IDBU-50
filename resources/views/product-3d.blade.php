<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $product->name }} — Produk 3R ramah lingkungan dari Kelurahan Rowosari. Lihat model 3D interaktif dan komposisi bahan daur ulang.">
    <title>{{ $product->name }} — 3D Viewer | KKN-T IDBU 50 ROWOSARI</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,700;0,7..72,800;1,7..72,400&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/product-3d.js'])
</head>
<body class="product-3d-page">

    {{-- =================== BACK NAVIGATION =================== --}}
    <a href="{{ route('home') }}#katalog" class="p3d-back" aria-label="Kembali ke katalog">
        <span class="material-symbols-outlined">arrow_back</span>
        <span class="p3d-back__text">Katalog</span>
    </a>

    {{-- =================== FLOATING PARTICLES BG =================== --}}
    <div class="p3d-bg-particles">
        @for($i = 0; $i < 20; $i++)
            <span class="p3d-particle" style="
                left: {{ rand(0, 100) }}%;
                top: {{ rand(0, 100) }}%;
                animation-duration: {{ rand(15, 30) }}s;
                animation-delay: {{ rand(0, 10) }}s;
                opacity: {{ rand(3, 12) / 100 }};
                width: {{ rand(2, 6) }}px;
                height: {{ rand(2, 6) }}px;
            "></span>
        @endfor
    </div>

    {{-- =================== MAIN LAYOUT =================== --}}
    <div class="p3d-container">

        {{-- LEFT: 3D Viewer --}}
        <div class="p3d-viewer">
            {{-- Loading overlay --}}
            <div class="p3d-loader" id="viewer3dLoader">
                <div class="p3d-loader__spinner"></div>
                <p class="p3d-loader__text">Memuat Model 3D...</p>
            </div>

            {{-- Three.js Canvas --}}
            <canvas
                id="product3dCanvas"
                data-model-type="{{ $product->model_type ?? 'default' }}"
            ></canvas>

            {{-- Controls hint --}}
            <div class="p3d-controls-hint">
                <div class="p3d-controls-hint__item">
                    <span class="material-symbols-outlined">mouse</span>
                    <span>Klik & Drag — Rotate</span>
                </div>
                <div class="p3d-controls-hint__item">
                    <span class="material-symbols-outlined">zoom_in</span>
                    <span>Scroll — Zoom</span>
                </div>
            </div>

            {{-- Product badge --}}
            <div class="p3d-badge">
                <span class="material-symbols-outlined">view_in_ar</span>
                3D Interactive
            </div>
        </div>

        {{-- RIGHT: Info Panel --}}
        <div class="p3d-info">
            <div class="p3d-info__inner">

                {{-- Category badge --}}
                <span class="p3d-category p3d-category--{{ $product->category }}">
                    {{ $product->category === 'organik' ? '🌱 Organik' : '✂️ Kriya' }}
                </span>

                {{-- Product name --}}
                <h1 class="p3d-title">{{ $product->name }}</h1>

                {{-- Price --}}
                <div class="p3d-price">{{ $product->formatted_price }}</div>

                {{-- Description --}}
                <p class="p3d-desc">{{ $product->description }}</p>

                {{-- ====== WASTE COMPOSITION SECTION ====== --}}
                @if($product->waste_composition && count($product->waste_composition) > 0)
                <div class="p3d-composition">
                    <div class="p3d-composition__header">
                        <span class="material-symbols-outlined">recycling</span>
                        <h3>Komposisi Bahan Daur Ulang</h3>
                    </div>
                    <p class="p3d-composition__subtitle">Persentase bahan sampah yang digunakan untuk membuat produk ini</p>

                    <div class="composition-list">
                        @foreach($product->waste_composition as $index => $material)
                        <div class="composition-item" style="--item-color: {{ $material['color'] }}">
                            <div class="composition-item__header">
                                <span class="composition-item__dot" style="background: {{ $material['color'] }}"></span>
                                <span class="composition-item__name">{{ $material['name'] }}</span>
                                <span class="composition-item__percent">{{ $material['percentage'] }}%</span>
                            </div>
                            <div class="composition-bar">
                                <div class="composition-bar__fill"
                                     data-width="{{ $material['percentage'] }}%"
                                     style="background: {{ $material['color'] }}; width: 0%;">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total waste impact --}}
                    <div class="p3d-impact">
                        <div class="p3d-impact__icon">
                            <span class="material-symbols-outlined">eco</span>
                        </div>
                        <div>
                            <strong>100% Bahan Daur Ulang</strong>
                            <p>Setiap pembelian membantu mengurangi sampah di lingkungan Kelurahan Rowosari</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ====== PRODUCT IMAGES ====== --}}
                @if($product->images->count() > 0)
                <div class="p3d-gallery">
                    <h4 class="p3d-gallery__title">
                        <span class="material-symbols-outlined">photo_library</span>
                        Foto Produk
                    </h4>
                    <div class="p3d-gallery__grid">
                        @foreach($product->images as $image)
                        <div class="p3d-gallery__item">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ====== BUY BUTTONS ====== --}}
                <div class="p3d-actions">
                    @if($product->shopee_link)
                    <a href="{{ $product->shopee_link }}" class="p3d-btn p3d-btn--primary" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        Beli di Shopee
                    </a>
                    @endif
                    @if($product->tokopedia_link)
                    <a href="{{ $product->tokopedia_link }}" class="p3d-btn p3d-btn--amber" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">storefront</span>
                        Beli di Tokopedia
                    </a>
                    @endif
                    <a href="{{ route('home') }}#katalog" class="p3d-btn p3d-btn--ghost">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali ke Katalog
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
