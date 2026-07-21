@extends('layouts.app')

@section('title', $news->title)

@section('meta_description', strip_tags($news->excerpt))

@section('head')
    <meta property="og:title" content="{{ $news->title }}" />
    <meta property="og:description" content="{{ $news->excerpt }}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ request()->url() }}" />
    @if($news->image_path)
    <meta property="og:image" content="{{ asset('storage/' . $news->image_path) }}" />
    @endif
@endsection

@section('content')
<div class="news-hero" style="position: relative; width: 100%; height: 60vh; min-height: 400px; background: var(--color-forest-dark); overflow: hidden; display: flex; align-items: flex-end;">
    @if($news->image_path)
        <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.6; z-index: 1;">
    @else
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, var(--color-emerald) 0%, var(--color-forest-dark) 100%); z-index: 1;"></div>
    @endif
    
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(26,26,26,0.9) 0%, rgba(26,26,26,0.4) 50%, rgba(26,26,26,0.1) 100%); z-index: 2;"></div>

    <div class="container" style="position: relative; z-index: 3; padding-bottom: var(--space-2xl);" data-aos="fade-up">
        <div class="news-breadcrumb" style="color: rgba(255,255,255,0.7); font-size: 0.9rem; margin-bottom: var(--space-md); display: flex; gap: 8px; align-items: center;">
            <a href="{{ route('home') }}" style="color: white; text-decoration: none;">Beranda</a>
            <span class="material-symbols-outlined" style="font-size: 1rem;">chevron_right</span>
            <a href="{{ route('news.index') }}" style="color: white; text-decoration: none;">Berita</a>
            <span class="material-symbols-outlined" style="font-size: 1rem;">chevron_right</span>
            <span style="color: var(--color-amber-light); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">{{ $news->title }}</span>
        </div>
        
        <h1 style="font-family: var(--font-heading); color: white; font-size: clamp(2rem, 4vw, 3.5rem); line-height: 1.2; margin-bottom: var(--space-md); text-shadow: 0 4px 12px rgba(0,0,0,0.3); max-width: 900px;">
            {{ $news->title }}
        </h1>
        
        <div class="news-meta-bar" style="display: flex; flex-wrap: wrap; gap: var(--space-lg); color: rgba(255,255,255,0.9); font-size: 0.95rem;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem; color: var(--color-amber-light);">calendar_today</span>
                {{ \Carbon\Carbon::parse($news->event_date)->isoFormat('dddd, D MMMM Y') }}
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem; color: var(--color-amber-light);">location_on</span>
                {{ $news->location }}
            </div>
            @if($news->category)
            <div style="display: flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 1.2rem; color: var(--color-amber-light);">folder</span>
                <a href="{{ route('news.index', ['category' => $news->category->slug]) }}" style="color: white; text-decoration: none; border-bottom: 1px dotted rgba(255,255,255,0.5);">{{ $news->category->name }}</a>
            </div>
            @endif
        </div>
    </div>
</div>

<section class="news-content-section" style="background: var(--color-cream-light); padding: var(--space-2xl) 0;">
    <div class="container">
        <div class="news-content-wrapper" style="max-width: 760px; margin: 0 auto;">
            <div class="ql-editor article-body" data-aos="fade-up">
                {!! $news->content !!}
            </div>

            @if($news->tags->count() > 0)
                <div style="margin-top: var(--space-xl); display: flex; gap: 8px; flex-wrap: wrap; align-items: center;" data-aos="fade-up">
                    <span style="font-size: 0.9rem; color: var(--color-slate); font-weight: 600; margin-right: 8px;">Tags:</span>
                    @foreach($news->tags as $tag)
                        <a href="{{ route('news.index', ['tag' => $tag->slug]) }}" style="background: rgba(74, 124, 89, 0.1); color: var(--color-forest-dark); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; text-decoration: none; transition: background 0.2s ease;">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
            
            <div class="article-share" style="margin-top: var(--space-2xl); padding-top: var(--space-xl); border-top: 1px solid rgba(0,0,0,0.1); display: flex; align-items: center; gap: var(--space-md);" data-aos="fade-up">
                <span style="font-weight: 600; color: var(--color-slate);">Bagikan Berita:</span>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . request()->url()) }}" target="_blank" class="btn btn-icon" style="background: #25D366; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    <i class="material-symbols-outlined">chat</i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-icon" style="background: #1877F2; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    <i class="material-symbols-outlined">thumb_up</i>
                </a>
            </div>
        </div>
    </div>
</section>

@if($relatedNews->count() > 0)
<section class="section section--sand related-news" style="background: var(--color-sand); padding: var(--space-2xl) 0;">
    <div class="container">
        <div class="section__header" style="text-align: left; margin-bottom: var(--space-xl);" data-aos="fade-up">
            <h2 class="section__title" style="font-size: 2rem;">Berita Terkait</h2>
        </div>
        
        <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--space-xl);">
            @foreach($relatedNews as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="news-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="text-decoration: none; display: flex; flex-direction: column; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.8); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 10px 30px rgba(92, 74, 61, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="news-card__image" style="height: 180px; width: 100%; overflow: hidden; position: relative; background: var(--color-cream-dark);">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-wood); opacity: 0.5;">newspaper</span>
                            </div>
                        @endif
                    </div>
                    <div class="news-card__content" style="padding: var(--space-lg); flex: 1; display: flex; flex-direction: column;">
                        <div class="news-card__meta" style="font-size: 0.8rem; color: var(--color-forest); font-weight: 600; margin-bottom: var(--space-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">calendar_today</span>
                                {{ \Carbon\Carbon::parse($item->event_date)->isoFormat('D MMMM Y') }}
                            </div>
                            @if($item->category)
                                <div style="background: var(--color-amber-light); color: var(--color-charcoal); padding: 2px 10px; border-radius: 20px; font-size: 0.75rem;">
                                    {{ $item->category->name }}
                                </div>
                            @endif
                        </div>
                        <h3 class="news-card__title" style="font-family: var(--font-heading); color: var(--color-slate); font-size: 1.15rem; margin-bottom: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $item->title }}
                        </h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<style>
    /* Styling for the article body to make it look like a premium blog post */
    .article-body {
        font-family: var(--font-body);
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
    }
    .article-body p {
        margin-bottom: 1.5em;
    }
    .article-body h2 {
        font-family: var(--font-heading);
        font-size: 2rem;
        color: var(--color-forest-dark);
        margin-top: 2em;
        margin-bottom: 0.8em;
        font-weight: 700;
    }
    .article-body h3 {
        font-family: var(--font-heading);
        font-size: 1.6rem;
        color: var(--color-forest);
        margin-top: 1.8em;
        margin-bottom: 0.6em;
        font-weight: 700;
    }
    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: var(--radius-lg);
        margin: 1.5em 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .article-body blockquote {
        border-left: 5px solid var(--color-amber);
        background: rgba(196, 166, 106, 0.1);
        padding: 1.5em 2em;
        margin: 2em 0;
        font-style: italic;
        color: var(--color-slate);
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
    }
    .article-body ul, .article-body ol {
        margin-bottom: 1.5em;
        padding-left: 2em;
    }
    .article-body li {
        margin-bottom: 0.5em;
    }
    .article-body a {
        color: var(--color-emerald);
        text-decoration: none;
        border-bottom: 1px dashed var(--color-emerald);
        transition: all 0.2s ease;
    }
    .article-body a:hover {
        color: var(--color-forest-dark);
        border-bottom-style: solid;
    }
    
    .news-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 35px rgba(92, 74, 61, 0.15) !important;
    }
    .news-card:hover .news-card__image img {
        transform: scale(1.05);
    }
</style>
@endsection
