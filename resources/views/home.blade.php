@extends('layouts.app')

@section('title', 'KKN-T IDBU 50 ROWOSARI — Pengelolaan Sampah Berkelanjutan')

@section('content')

{{-- ======================== HERO SECTION ======================== --}}
<section class="hero" id="hero">
    <div class="hero__bg">
        @if(!empty($settings['hero_video_url']))
            <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover; filter: blur(1.5px);">
                <source src="{{ $settings['hero_video_url'] }}" type="video/mp4">
            </video>
        @else
            <img src="{{ asset('images/hero-sawah.png') }}" alt="Desa Rowosari">
        @endif
    </div>
    <div class="hero__overlay"></div>
    <div class="hero__particles">
        <span class="particle particle--leaf" style="left: 10%; animation-duration: 15s; animation-delay: 0s;">🍃</span>
        <span class="particle particle--dot" style="left: 20%; animation-duration: 25s; animation-delay: 5s;"></span>
        <span class="particle particle--recycle" style="left: 35%; animation-duration: 18s; animation-delay: 2s;">♻️</span>
        <span class="particle particle--dot" style="left: 50%; animation-duration: 20s; animation-delay: 8s;"></span>
        <span class="particle particle--leaf" style="left: 65%; animation-duration: 16s; animation-delay: 1s;">🍂</span>
        <span class="particle particle--dot" style="left: 80%; animation-duration: 22s; animation-delay: 4s;"></span>
        <span class="particle particle--recycle" style="left: 90%; animation-duration: 19s; animation-delay: 6s;">♻️</span>
    </div>

    <div class="hero__content">
        <div class="hero__badge-row" data-aos="fade-down">
            <span class="hero__badge">♻️ Reduce</span>
            <span class="hero__badge">🔄 Reuse</span>
            <span class="hero__badge">🌱 Recycle</span>
        </div>

        <h1 class="hero__title" data-aos="fade-up">
            {{ $settings['hero_headline'] ?? 'Rowosari Bersih, Rowosari Berdaya' }}
        </h1>

        <p class="hero__subtitle" data-aos="fade-up" data-aos-delay="100">
            {{ $settings['hero_subheadline'] ?? 'Program pengelolaan sampah 3R oleh KKN Undip di Kelurahan Rowosari, Tembalang.' }}
        </p>

        <div class="hero__actions" data-aos="fade-up" data-aos-delay="200">
            <a href="#katalog" class="btn btn-primary btn-lg">
                <span class="material-symbols-outlined">shopping_bag</span>
                Lihat Produk Kami
            </a>
            <a href="#tentang" class="btn btn-outline btn-lg">
                <span class="material-symbols-outlined">info</span>
                Tentang Kami
            </a>
        </div>
    </div>

    {{-- Diagonal divider --}}
    <div class="section__divider">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path d="M0,60 L1440,0 L1440,60 Z" fill="var(--color-white)"></path>
        </svg>
    </div>
</section>

{{-- ======================== VIDEO PROFIL SECTION ======================== --}}
<section class="section section--white video-section" id="video" style="padding-bottom: 0;">
    <div class="container">
        <div class="section__header" data-aos="fade-up" style="margin-bottom: 0;">
            <span class="section__label">🎬 Kenali Kami</span>
            <h2 class="section__title">Video Profil Rowosari</h2>
            <p class="section__subtitle">Mengenal lebih dekat program pengelolaan sampah 3R di Kelurahan Rowosari</p>
        </div>
    </div>

    <div class="video-wrapper video-wrapper--full" data-aos="zoom-in" data-aos-delay="100" style="position:relative;">
        @if(!empty($settings['profil_video_url']))
            @if(str_contains($settings['profil_video_url'], 'youtube') || str_contains($settings['profil_video_url'], 'youtu.be') || str_contains($settings['profil_video_url'], 'drive.google.com'))
                @php
                    $videoUrl = $settings['profil_video_url'];
                    if (str_contains($videoUrl, 'youtube') || str_contains($videoUrl, 'youtu.be')) {
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $match)) {
                            $videoUrl = 'https://www.youtube.com/embed/' . $match[1];
                        }
                    } elseif (str_contains($videoUrl, 'drive.google.com')) {
                        if (preg_match('/drive\.google\.com\/(?:file\/d\/|open\?id=)([a-zA-Z0-9_-]+)/i', $videoUrl, $match)) {
                            $videoUrl = 'https://drive.google.com/file/d/' . $match[1] . '/preview';
                        } else {
                            $videoUrl = str_replace(['/view', '/edit'], '/preview', $videoUrl);
                            $videoUrl = preg_replace('/\?.*/', '', $videoUrl);
                        }
                    }
                @endphp
                <iframe
                    src="{{ $videoUrl }}"
                    title="Video Profil Rowosari"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
            @else
                <video controls>
                    <source src="{{ $settings['profil_video_url'] }}" type="video/mp4">
                </video>
            @endif
        @else
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:var(--color-cream-dark);">
                <div style="text-align:center;color:var(--color-muted);">
                    <span class="material-symbols-outlined" style="font-size:3rem;">videocam_off</span>
                    <p style="margin-top:1rem;">Video belum ditambahkan</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Decorative blobs --}}
    <div class="blob blob--green" style="width:300px;height:300px;top:-100px;right:-100px;"></div>
    <div class="blob blob--amber" style="width:200px;height:200px;bottom:-50px;left:-50px;"></div>
</section>

{{-- ======================== TENTANG SECTION ======================== --}}
<section class="section section--cream" id="tentang">
    <div class="container">
        <div class="about-grid">
            <div class="about__image" data-aos="fade-right">
                @if(!empty($settings['tentang_image']))
                    <img src="{{ asset('storage/' . $settings['tentang_image']) }}"
                         alt="Pengelolaan sampah di Rowosari"
                         loading="lazy">
                @else
                    <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=800&h=600&fit=crop"
                         alt="Pengelolaan sampah di Rowosari"
                         loading="lazy">
                @endif
            </div>

            <div class="about__text" data-aos="fade-left">
                <span class="section__label">🌿 Tentang Program</span>
                <h2>Mengubah Sampah Menjadi Berkah</h2>
                <p>{{ $settings['tentang_text'] ?? 'Program KKN Kelurahan Rowosari berfokus pada pengelolaan sampah berbasis 3R.' }}</p>
                <a href="#katalog" class="btn btn-outline-dark">
                    <span class="material-symbols-outlined">arrow_forward</span>
                    Lihat Produk
                </a>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="stats-row" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon"><span class="material-symbols-outlined">delete_sweep</span></div>
                <div class="stat-card__number" data-target="{{ $settings['stat_sampah'] ?? 500 }}" data-suffix=" Kg">0</div>
                <div class="stat-card__label">Sampah Terolah</div>
            </div>
            <div class="stat-card stat-card--amber">
                <div class="stat-card__icon"><span class="material-symbols-outlined">inventory_2</span></div>
                <div class="stat-card__number" data-target="{{ $settings['stat_produk'] ?? 25 }}" data-suffix="+">0</div>
                <div class="stat-card__label">Produk Dibuat</div>
            </div>
            <div class="stat-card stat-card--teal">
                <div class="stat-card__icon"><span class="material-symbols-outlined">group</span></div>
                <div class="stat-card__number" data-target="{{ $settings['stat_warga'] ?? 150 }}" data-suffix="+">0</div>
                <div class="stat-card__label">Warga Terlibat</div>
            </div>
        </div>

        {{-- Feature Cards --}}
        <div class="features-row">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card__icon">
                    <span class="material-symbols-outlined">eco</span>
                </div>
                <h4>Inovasi Hijau</h4>
                <p>Mengolah sampah organik dan anorganik menjadi produk baru yang bermanfaat dan bernilai ekonomis.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card__icon">
                    <span class="material-symbols-outlined">store</span>
                </div>
                <h4>Ekonomi Kreatif</h4>
                <p>Membangun usaha mikro berbasis daur ulang untuk meningkatkan pendapatan warga sekitar.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card__icon">
                    <span class="material-symbols-outlined">groups</span>
                </div>
                <h4>Pemberdayaan Masyarakat</h4>
                <p>Edukasi dan pelatihan pengelolaan sampah bersama warga untuk menciptakan lingkungan bersih.</p>
            </div>
        </div>
    </div>
</section>

{{-- ======================== BERITA TERBARU SECTION ======================== --}}
<section class="section section--sand" id="berita" style="background: var(--color-sand);">
    <div class="container">
        <div class="section__header" data-aos="fade-up">
            <span class="section__label">📰 Update KKN</span>
            <h2 class="section__title">Berita Harian</h2>
            <p class="section__subtitle" style="margin-bottom: var(--space-md);">Ikuti perkembangan program dan kegiatan KKN-T IDBU 50 di Kelurahan Rowosari</p>
            <a href="{{ route('news.index') }}" class="btn btn-outline-dark" style="border-radius: 30px; padding: 6px 16px; display: inline-flex; align-items: center; gap: 4px; font-size: 0.9rem; color: var(--color-forest); border-color: var(--color-forest);">
                Lihat Semua Berita <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_forward</span>
            </a>
        </div>

        @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--space-xl); margin-bottom: var(--space-xl);">
                @foreach($latestNews as $news)
                    <a href="{{ route('news.show', $news->slug) }}" class="news-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="text-decoration: none; display: flex; flex-direction: column; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.8); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 10px 30px rgba(92, 74, 61, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="news-card__image" style="height: 200px; width: 100%; overflow: hidden; position: relative; background: var(--color-cream-dark);">
                            @if($news->image_path)
                                <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-wood); opacity: 0.5;">newspaper</span>
                                </div>
                            @endif
                        </div>
                        <div class="news-card__content" style="padding: var(--space-lg); flex: 1; display: flex; flex-direction: column;">
                            <div class="news-card__meta" style="font-size: 0.85rem; color: var(--color-forest); font-weight: 600; margin-bottom: var(--space-sm); display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">calendar_today</span>
                                {{ \Carbon\Carbon::parse($news->event_date)->isoFormat('D MMMM Y') }}
                            </div>
                            <h3 class="news-card__title" style="font-family: var(--font-heading); color: var(--color-slate); font-size: 1.25rem; margin-bottom: var(--space-sm); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $news->title }}
                            </h3>
                            <p class="news-card__excerpt" style="color: var(--color-earth); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                                {{ $news->excerpt }}
                            </p>
                            <div style="margin-top: var(--space-md); color: var(--color-forest); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                Baca selengkapnya <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>


        @else
            <div style="text-align: center; padding: 40px 20px; background: rgba(255,255,255,0.5); border-radius: var(--radius-lg); backdrop-filter: blur(5px);" data-aos="fade-up">
                <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-wood); opacity: 0.5; margin-bottom: 10px;">hourglass_empty</span>
                <p style="color: var(--color-earth); margin: 0;">Belum ada berita kegiatan yang ditayangkan saat ini.</p>
            </div>
        @endif
    </div>
</section>

<style>
    .news-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 35px rgba(92, 74, 61, 0.15) !important;
    }
    .news-card:hover .news-card__image img {
        transform: scale(1.05);
    }
</style>

{{-- ======================== GALERI SECTION ======================== --}}
<section class="section section--white" id="galeri">
    <div class="container-wide">
        <div class="section__header" data-aos="fade-up">
            <span class="section__label">📸 Galeri</span>
            <h2 class="section__title">Dokumentasi Kegiatan</h2>
            <p class="section__subtitle">Momen-momen kegiatan KKN di Kelurahan Rowosari</p>
        </div>

        @if($gallery->count() > 0)
            <div class="gallery-container" data-aos="fade-up" data-aos-delay="100">
                <button class="gallery-scroll-btn gallery-scroll-btn--left" aria-label="Geser kiri" onclick="this.nextElementSibling.nextElementSibling.scrollBy({left: -320, behavior: 'smooth'})">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="gallery-scroll-btn gallery-scroll-btn--right" aria-label="Geser kanan" onclick="this.nextElementSibling.scrollBy({left: 320, behavior: 'smooth'})">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <div class="gallery-scroll">
                @foreach($gallery as $photo)
                    <div class="gallery-card">
                        <img src="{{ asset('storage/' . $photo->image_path) }}"
                             alt="{{ $photo->title ?? 'Foto kegiatan' }}"
                             loading="lazy">
                        @if($photo->title)
                            <div class="gallery-card__overlay">
                                <p>{{ $photo->title }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
            <div class="gallery-hint">
                <span class="material-symbols-outlined">swipe</span>
                Geser untuk melihat lebih banyak
            </div>
        @else
            <div class="gallery-container" data-aos="fade-up" data-aos-delay="100">
                <button class="gallery-scroll-btn gallery-scroll-btn--left" aria-label="Geser kiri" onclick="this.nextElementSibling.nextElementSibling.scrollBy({left: -320, behavior: 'smooth'})">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="gallery-scroll-btn gallery-scroll-btn--right" aria-label="Geser kanan" onclick="this.nextElementSibling.scrollBy({left: 320, behavior: 'smooth'})">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <div class="gallery-scroll">
                {{-- Placeholder gallery cards --}}
                @for($i = 1; $i <= 5; $i++)
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-{{ $i == 1 ? '1532996122724-e3c354a0b15b' : ($i == 2 ? '1542601906990-b4d3fb778b09' : ($i == 3 ? '1558618666-fcd25c85f82e' : ($i == 4 ? '1604187351574-c75ca79f5807' : '1611284446314-60a58ac0deb9'))) }}?w=500&h=350&fit=crop"
                             alt="Kegiatan KKN {{ $i }}"
                             loading="lazy">
                        <div class="gallery-card__overlay">
                            <p>Kegiatan KKN {{ $i }}</p>
                        </div>
                    </div>
                @endfor
                </div>
            </div>
            <div class="gallery-hint">
                <span class="material-symbols-outlined">swipe</span>
                Geser untuk melihat lebih banyak
            </div>
        @endif
    </div>
</section>

{{-- ======================== KATALOG SECTION ======================== --}}
<section class="section section--cream" id="katalog">
    <div class="container">
        <div class="section__header" data-aos="fade-up">
            <span class="section__label">🛒 Katalog</span>
            <h2 class="section__title">Produk 3R Kami</h2>
            <p class="section__subtitle">Produk ramah lingkungan hasil kreativitas warga Kelurahan Rowosari</p>
        </div>

        <div class="product-grid">
            @forelse($products as $index => $product)
                <div class="product-card"
                     data-product-id="{{ $product->id }}"
                     data-aos="fade-up"
                     data-aos-delay="{{ ($index % 3) * 100 }}">

                    <div class="product-card__image">
                        @if($index === 0)
                            <div class="product-card__ribbon">Terbaru</div>
                        @endif

                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                        @else
                            <div class="placeholder-img" style="height: 100%; display: flex; align-items: center; justify-content: center; background: var(--color-cream-dark); color: var(--color-muted);">
                                <span class="material-symbols-outlined" style="font-size: 3rem;">image</span>
                            </div>
                        @endif

                        <span class="product-card__badge product-card__badge--{{ $product->category }}">
                            {{ $product->category === 'organik' ? '🌱 Organik' : '✂️ Kriya' }}
                        </span>
                    </div>

                    <div class="product-card__body">
                        <h3 class="product-card__name">{{ $product->name }}</h3>
                        <p class="product-card__desc">{{ $product->description }}</p>
                        <div class="product-card__footer">
                            <span class="product-card__price">{{ $product->formatted_price }}</span>
                            <div style="display:flex; gap: 6px; align-items:center;">
                                @if(str_contains(strtolower($product->name), 'kompos') || str_contains(strtolower($product->name), 'pupuk'))
                                <a href="{{ route('kompos.eksplorasi') }}"
                                   class="product-card__3d-btn"
                                   title="Eksplorasi Kompos"
                                   onclick="event.stopPropagation();"
                                   style="background: linear-gradient(135deg, var(--color-forest), var(--color-amber)); color: white;">
                                    <span class="material-symbols-outlined">layers</span>
                                </a>
                                @endif
                                @if(str_contains(strtolower($product->name), 'coaster') || str_contains(strtolower($product->name), 'terrazzo'))
                                <a href="{{ route('coaster.eksplorasi') }}"
                                   class="product-card__3d-btn"
                                   title="Eksplorasi Coaster"
                                   onclick="event.stopPropagation();"
                                   style="background: linear-gradient(135deg, var(--color-forest), var(--color-amber)); color: white;">
                                    <span class="material-symbols-outlined">layers</span>
                                </a>
                                @endif
                                @if($product->model_type)
                                <a href="{{ route('product.3d', $product->slug) }}"
                                   class="product-card__3d-btn"
                                   title="Lihat Model 3D"
                                   onclick="event.stopPropagation();">
                                    <span class="material-symbols-outlined">view_in_ar</span>
                                </a>
                                @endif
                                <button class="product-card__action" aria-label="Lihat detail">
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--color-muted);">
                    <span class="material-symbols-outlined" style="font-size: 3rem;">inventory_2</span>
                    <p style="margin-top: 1rem;">Belum ada produk yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ======================== FOOTER / KONTAK SECTION ======================== --}}
<footer class="footer" id="kontak">
    {{-- Decorative blobs --}}
    <div class="footer__blob" style="width:400px;height:400px;top:-200px;right:-100px;background:var(--color-forest);"></div>
    <div class="footer__blob" style="width:300px;height:300px;bottom:-100px;left:-50px;background:var(--color-amber);"></div>

    {{-- Diagonal top --}}
    <div style="position:absolute;top:-1px;left:0;right:0;overflow:hidden;line-height:0;">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style="display:block;width:100%;height:60px;">
            <path d="M0,0 L1440,60 L0,60 Z" fill="var(--color-cream)"></path>
        </svg>
    </div>

    <div class="container">
        <div class="footer__grid" data-aos="fade-up">
            <div>
                <div class="footer__brand" style="display: flex; align-items: center; gap: 8px;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Rowosari 3R" style="height: 82px; width: auto; object-fit: contain;">
                    KKN-T IDBU 50 Kelurahan Rowosari
                </div>
                <p class="footer__desc">
                    Program KKN Kelurahan Rowosari — membangun lingkungan bersih dan ekonomi kreatif melalui pengelolaan sampah berbasis 3R (Reduce, Reuse, Recycle).
                </p>
            </div>

            <div>
                <h4 class="footer__heading">Navigasi</h4>
                <ul class="footer__links">
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#tentang">Tentang Program</a></li>
                    <li><a href="#galeri">Galeri</a></li>
                    <li><a href="#katalog">Katalog Produk</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer__heading">Kontak</h4>
                <div class="footer__contact-item">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>{{ $settings['contact_address'] ?? 'Kelurahan Rowosari, Tembalang, Semarang' }}</span>
                </div>
                <div class="footer__contact-item">
                    <span class="material-symbols-outlined">mail</span>
                    <span>{{ $settings['contact_email'] ?? 'kkn.rowosari3r@gmail.com' }}</span>
                </div>
                <div class="footer__contact-item">
                    <span class="material-symbols-outlined">phone</span>
                    <span>{{ $settings['contact_phone'] ?? '+62 812-xxxx-xxxx' }}</span>
                </div>
                @if(!empty($settings['contact_instagram']))
                    <div class="footer__contact-item">
                        <span class="material-symbols-outlined">photo_camera</span>
                        <span>{{ $settings['contact_instagram'] }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer__bottom">
            <p class="footer__copyright">
                &copy; {{ date('Y') }} KKN-T IDBU 50 ROWOSARI — Universitas Diponegoro. All rights reserved.
            </p>
        </div>
    </div>
</footer>

@endsection
