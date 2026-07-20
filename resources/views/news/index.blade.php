@extends('layouts.app')

@section('title', 'Berita Harian')

@section('content')
<section class="section section--forest" style="padding-top: 140px; padding-bottom: 80px; position: relative; overflow: hidden;">
    {{-- Background Image with Overlay --}}
    <img src="{{ asset('images/hero-sawah.png') }}" alt="Rowosari" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.3; z-index: 0;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(34, 60, 42, 0.9) 0%, rgba(74, 124, 89, 0.8) 100%); z-index: 1;"></div>
    
    {{-- Decorative Particles --}}
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; pointer-events: none; opacity: 0.5;">
        <div style="position: absolute; top: 20%; left: 10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -10%; right: 5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(196,166,106,0.15) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    </div>

    <div class="container" style="position: relative; z-index: 3;">
        <div style="margin-bottom: var(--space-xl);" data-aos="fade-down">
            <a href="{{ route('home') }}" class="btn btn-outline" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: white; border-radius: 30px; padding: 6px 16px; display: inline-flex; align-items: center; gap: 6px; font-size: 0.9rem; backdrop-filter: blur(4px);">
                <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_back</span> Kembali ke Beranda
            </a>
        </div>
        <div class="section__header" data-aos="fade-up" style="margin-bottom: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
            <span class="section__label" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3); color: white;">📰 Berita Harian</span>
            <h1 class="section__title" style="font-size: clamp(2.5rem, 5vw, 3.5rem); text-shadow: 0 4px 15px rgba(0,0,0,0.3);">Semua Berita & Kegiatan</h1>
            <p class="section__subtitle" style="color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto; font-size: 1.15rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                Daftar lengkap berita dan kegiatan terbaru dari KKN-T IDBU 50 Rowosari.
            </p>
        </div>
    </div>
    
    {{-- Diagonal divider --}}
    <div class="section__divider">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path d="M0,60 L1440,0 L1440,60 Z" fill="var(--color-sand)"></path>
        </svg>
    </div>
</section>

<section class="section section--sand" style="background: var(--color-sand); min-height: 60vh; padding-top: var(--space-xl);">
    <div class="container">
        
        {{-- Search Bar --}}
        <div style="max-width: 700px; margin: 0 auto var(--space-xl) auto;" data-aos="fade-up">
            <form action="{{ route('news.index') }}" method="GET" style="display: flex; gap: 12px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); padding: 8px; border-radius: 50px; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 30px rgba(92, 74, 61, 0.08);">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('tag'))
                    <input type="hidden" name="tag" value="{{ request('tag') }}">
                @endif
                <div style="flex: 1; position: relative; display: flex; align-items: center;">
                    <span class="material-symbols-outlined" style="position: absolute; left: 20px; color: var(--color-forest); font-size: 1.4rem;">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita atau kegiatan..." style="width: 100%; padding: 14px 20px 14px 56px; border-radius: 40px; border: none; background: transparent; font-size: 1.05rem; font-family: var(--font-body); color: var(--color-slate); outline: none;">
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius: 40px; padding: 0 30px; font-weight: 600; letter-spacing: 0.5px;">Cari</button>
            </form>
        </div>

        {{-- Filter Chips --}}
        <div class="filter-chips" style="margin-bottom: var(--space-2xl); display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;" data-aos="fade-up">
            <a href="{{ route('news.index') }}" class="btn {{ !request('category') && !request('tag') && !request('q') ? 'btn-primary' : 'btn-outline' }}" style="border-radius: 30px; padding: 8px 20px; font-size: 0.9rem;">
                Semua Berita
            </a>
            
            @foreach($categories as $cat)
                <a href="{{ route('news.index', ['category' => $cat->slug, 'q' => request('q')]) }}" class="btn {{ request('category') == $cat->slug ? 'btn-primary' : 'btn-outline' }}" style="border-radius: 30px; padding: 8px 20px; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 1.1rem;">folder</span> {{ $cat->name }}
                </a>
            @endforeach

            @if(request('tag'))
                <a href="{{ route('news.index', ['q' => request('q'), 'category' => request('category')]) }}" class="btn btn-primary" style="border-radius: 30px; padding: 8px 20px; font-size: 0.9rem; background: linear-gradient(135deg, var(--color-emerald) 0%, var(--color-forest) 100%); display: inline-flex; align-items: center; gap: 6px;">
                    #{{ request('tag') }} <span class="material-symbols-outlined" style="font-size: 1.1rem;">close</span>
                </a>
            @endif
        </div>
        
        {{-- Search Result Indicator --}}
        @if(request('q'))
            <div style="text-align: center; margin-bottom: var(--space-xl); color: var(--color-slate);" data-aos="fade-up">
                Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong>
                <a href="{{ route('news.index', ['category' => request('category'), 'tag' => request('tag')]) }}" style="margin-left: 8px; color: var(--color-danger); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 4px;">
                    <span class="material-symbols-outlined" style="font-size: 1rem;">cancel</span> Bersihkan
                </a>
            </div>
        @endif

        @if($news->count() > 0)
            <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--space-xl); margin-bottom: var(--space-2xl);">
                @foreach($news as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="news-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 % 300 }}" style="text-decoration: none; display: flex; flex-direction: column; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.8); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 10px 30px rgba(92, 74, 61, 0.06); transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);">
                        <div class="news-card__image" style="height: 220px; width: 100%; overflow: hidden; position: relative; background: var(--color-cream-dark);">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(74,124,89,0.1) 0%, rgba(196,166,106,0.1) 100%);">
                                    <span class="material-symbols-outlined" style="font-size: 4rem; color: var(--color-forest); opacity: 0.3;">newspaper</span>
                                </div>
                            @endif
                            
                            @if($item->category)
                                <div style="position: absolute; top: 16px; right: 16px; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); color: var(--color-forest-dark); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 1rem;">folder</span> {{ $item->category->name }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="news-card__content" style="padding: var(--space-lg); flex: 1; display: flex; flex-direction: column;">
                            <div class="news-card__meta" style="font-size: 0.85rem; color: var(--color-forest); font-weight: 600; margin-bottom: var(--space-sm); display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem;">calendar_today</span>
                                {{ \Carbon\Carbon::parse($item->event_date)->isoFormat('D MMMM Y') }}
                            </div>
                            
                            <h3 class="news-card__title" style="font-family: var(--font-heading); color: var(--color-slate); font-size: 1.35rem; margin-bottom: var(--space-sm); line-height: 1.4; font-weight: 700; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s ease;">
                                {{ $item->title }}
                            </h3>
                            
                            <p class="news-card__excerpt" style="color: var(--color-earth); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                                {{ $item->excerpt }}
                            </p>
                            
                            @if($item->tags->count() > 0)
                                <div style="margin-top: 12px; display: flex; gap: 6px; flex-wrap: wrap;">
                                    @foreach($item->tags->take(3) as $tag)
                                        <span style="font-size: 0.75rem; background: rgba(74, 124, 89, 0.08); padding: 4px 10px; border-radius: 6px; color: var(--color-forest-dark); font-weight: 600;">#{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div style="margin-top: var(--space-lg); padding-top: var(--space-md); border-top: 1px solid rgba(0,0,0,0.06); color: var(--color-forest); font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: space-between;">
                                <span>Baca selengkapnya</span>
                                <span class="material-symbols-outlined" style="font-size: 1.2rem; transition: transform 0.2s ease;" class="arrow-icon">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pagination-wrapper" style="display: flex; justify-content: center; margin-top: var(--space-2xl);">
                {{ $news->links('vendor.pagination.custom') }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.7); border-radius: var(--radius-xl); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.8); box-shadow: 0 10px 30px rgba(92, 74, 61, 0.05); max-width: 600px; margin: 0 auto;" data-aos="fade-up">
                <div style="width: 80px; height: 80px; background: var(--color-cream); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px auto;">
                    <span class="material-symbols-outlined" style="font-size: 2.5rem; color: var(--color-forest);">search_off</span>
                </div>
                <h3 style="color: var(--color-forest-dark); font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 12px;">Pencarian Tidak Ditemukan</h3>
                <p style="color: var(--color-earth); margin-bottom: 24px; font-size: 1.05rem;">Maaf, kami tidak dapat menemukan berita yang sesuai dengan kriteria filter atau pencarianmu.</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <a href="{{ route('news.index') }}" class="btn btn-primary" style="border-radius: 30px; padding: 10px 24px;">Kembali ke Semua Berita</a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .news-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 20px 40px rgba(92, 74, 61, 0.12) !important;
        border-color: rgba(74, 124, 89, 0.2) !important;
    }
    .news-card:hover .news-card__image img {
        transform: scale(1.08);
    }
    .news-card:hover .news-card__title {
        color: var(--color-forest);
    }
    .news-card:hover .arrow-icon {
        transform: translateX(4px);
    }
    
    input[name="q"]:focus {
        background: transparent !important;
        box-shadow: none !important;
    }
</style>
@endsection
