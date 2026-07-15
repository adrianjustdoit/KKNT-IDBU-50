@extends('layouts.app')

@section('title', 'Berita Harian')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, var(--color-emerald) 0%, var(--color-forest-dark) 100%); padding: 120px 0 60px; text-align: center; color: white;">
    <div class="container" data-aos="fade-up">
        <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: var(--space-sm); font-weight: 700;">Berita Harian</h1>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">Daftar lengkap berita dan kegiatan terbaru dari KKN-T IDBU 50 Rowosari.</p>
    </div>
</div>

<section class="section section--sand" style="background: var(--color-sand); min-height: 60vh;">
    <div class="container">
        @if($news->count() > 0)
            <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--space-xl); margin-bottom: var(--space-2xl);">
                @foreach($news as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="news-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 % 300 }}" style="text-decoration: none; display: flex; flex-direction: column; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.8); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 10px 30px rgba(92, 74, 61, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="news-card__image" style="height: 200px; width: 100%; overflow: hidden; position: relative; background: var(--color-cream-dark);">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-wood); opacity: 0.5;">newspaper</span>
                                </div>
                            @endif
                        </div>
                        <div class="news-card__content" style="padding: var(--space-lg); flex: 1; display: flex; flex-direction: column;">
                            <div class="news-card__meta" style="font-size: 0.85rem; color: var(--color-forest); font-weight: 600; margin-bottom: var(--space-sm); display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">calendar_today</span>
                                {{ \Carbon\Carbon::parse($item->event_date)->isoFormat('D MMMM Y') }}
                            </div>
                            <h3 class="news-card__title" style="font-family: var(--font-heading); color: var(--color-slate); font-size: 1.25rem; margin-bottom: var(--space-sm); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $item->title }}
                            </h3>
                            <p class="news-card__excerpt" style="color: var(--color-earth); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                                {{ $item->excerpt }}
                            </p>
                            <div style="margin-top: var(--space-md); color: var(--color-forest); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                Baca selengkapnya <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pagination-wrapper" style="display: flex; justify-content: center; margin-top: var(--space-2xl);">
                {{ $news->links('vendor.pagination.custom') }}
            </div>
        @else
            <div style="text-align: center; padding: 80px 20px; background: rgba(255,255,255,0.5); border-radius: var(--radius-lg); backdrop-filter: blur(5px);" data-aos="fade-up">
                <span class="material-symbols-outlined" style="font-size: 4rem; color: var(--color-wood); opacity: 0.5; margin-bottom: 20px;">hourglass_empty</span>
                <h3 style="color: var(--color-forest-dark); font-family: var(--font-heading);">Belum Ada Berita</h3>
                <p style="color: var(--color-earth); margin-bottom: 0;">Berita dan kegiatan terbaru akan segera ditambahkan di sini.</p>
                <div style="margin-top: var(--space-lg);">
                    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
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
@endsection
