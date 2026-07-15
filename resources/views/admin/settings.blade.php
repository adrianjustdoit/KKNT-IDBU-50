@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
    <div class="admin-header">
        <h1>Pengaturan Website</h1>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Hero Section --}}
        <div class="admin-card" style="margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">
                <span class="material-symbols-outlined" style="vertical-align:middle;margin-right:8px;">title</span>
                Teks Hero
            </h3>

            <div class="form-group">
                <label class="form-label" for="hero_headline">Headline Utama</label>
                <input type="text" id="hero_headline" name="hero_headline" class="form-input"
                       value="{{ $settings['hero_headline'] ?? '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="hero_subheadline">Sub-headline</label>
                <textarea id="hero_subheadline" name="hero_subheadline" class="form-textarea" style="min-height:80px;">{{ $settings['hero_subheadline'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- About Section --}}
        <div class="admin-card" style="margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">
                <span class="material-symbols-outlined" style="vertical-align:middle;margin-right:8px;">info</span>
                Tentang Program
            </h3>

            <div class="form-group">
                <label class="form-label" for="tentang_text">Teks Tentang Kami</label>
                <textarea id="tentang_text" name="tentang_text" class="form-textarea">{{ $settings['tentang_text'] ?? '' }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="tentang_image">Gambar Tentang Program</label>
                <div class="image-upload" onclick="document.getElementById('tentang_image').click()">
                    <span class="material-symbols-outlined image-upload__icon">upload_file</span>
                    <p class="image-upload__text">Klik untuk memilih gambar baru</p>
                    <input type="file" id="tentang_image" name="tentang_image" class="form-input" style="display:none;" accept="image/*">
                </div>
                
                <div id="image-preview" class="image-preview-grid">
                    @if(!empty($settings['tentang_image']))
                        <div class="image-preview-item">
                            <img src="{{ asset('storage/' . $settings['tentang_image']) }}" alt="Gambar Tentang Program">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="admin-card" style="margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">
                <span class="material-symbols-outlined" style="vertical-align:middle;margin-right:8px;">monitoring</span>
                Statistik
            </h3>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="stat_sampah">Sampah Terolah (Kg)</label>
                    <input type="number" id="stat_sampah" name="stat_sampah" class="form-input"
                           value="{{ $settings['stat_sampah'] ?? 0 }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="stat_produk">Jumlah Produk</label>
                    <input type="number" id="stat_produk" name="stat_produk" class="form-input"
                           value="{{ $settings['stat_produk'] ?? 0 }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="stat_warga">Warga Terlibat</label>
                    <input type="number" id="stat_warga" name="stat_warga" class="form-input"
                           value="{{ $settings['stat_warga'] ?? 0 }}">
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="admin-card" style="margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">
                <span class="material-symbols-outlined" style="vertical-align:middle;margin-right:8px;">contact_mail</span>
                Informasi Kontak
            </h3>

            <div class="form-group">
                <label class="form-label" for="contact_address">Alamat</label>
                <input type="text" id="contact_address" name="contact_address" class="form-input"
                       value="{{ $settings['contact_address'] ?? '' }}">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="contact_email">Email</label>
                    <input type="email" id="contact_email" name="contact_email" class="form-input"
                           value="{{ $settings['contact_email'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_phone">No. Telepon</label>
                    <input type="text" id="contact_phone" name="contact_phone" class="form-input"
                           value="{{ $settings['contact_phone'] ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_instagram">Instagram</label>
                <input type="text" id="contact_instagram" name="contact_instagram" class="form-input"
                       value="{{ $settings['contact_instagram'] ?? '' }}" placeholder="@username">
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary btn-lg">
                <span class="material-symbols-outlined">save</span>
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.initImageUpload === 'function') {
                window.initImageUpload('tentang_image', 'image-preview');
            }
        });
    </script>
@endsection
