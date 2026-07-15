@extends('layouts.admin')

@section('title', 'Kelola Video')

@section('content')
    <div class="admin-header">
        <h1>Kelola Video</h1>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.media.video.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="hero_video_url">Video Hero (Background)</label>
                <input type="text" id="hero_video_url" name="hero_video_url" class="form-input"
                       value="{{ $heroVideoUrl }}"
                       placeholder="URL video MP4 (opsional, akan muncul di background hero)">
                <p class="form-hint">Masukkan URL langsung ke file video MP4. Kosongkan untuk menggunakan gradient background.</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="profil_video_url">Video Profil (YouTube Embed)</label>
                <input type="text" id="profil_video_url" name="profil_video_url" class="form-input"
                       value="{{ $profilVideoUrl }}"
                       placeholder="https://www.youtube.com/embed/VIDEO_ID">
                <p class="form-hint">Masukkan URL embed YouTube. Format: https://www.youtube.com/embed/VIDEO_ID</p>
            </div>

            @if(!empty($profilVideoUrl))
                @php
                    $videoUrl = $profilVideoUrl;
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
                <div class="form-group">
                    <label class="form-label">Preview Video Profil</label>
                    <div class="video-wrapper" style="max-width:500px;">
                        <iframe src="{{ $videoUrl }}" title="Preview" allowfullscreen></iframe>
                    </div>
                </div>
            @endif

            <div style="display:flex;justify-content:flex-end;margin-top:var(--space-xl);">
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Video
                </button>
            </div>
        </form>
    </div>
@endsection
