@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.author')

@php
    $routePrefix = auth()->user()->isAdmin() ? 'admin.' : 'author.';
@endphp

@section('title', 'Tambah Berita')

@section('content')
    <div class="admin-header">
        <h1>Tambah Berita</h1>
        <div class="admin-header__actions">
            <a href="{{ route($routePrefix . 'news.index') }}" class="btn btn-outline">
                <span class="material-symbols-outlined">arrow_back</span> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route($routePrefix . 'news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-xl); align-items: start; margin-top: var(--space-lg);">
            <style>
                @media (min-width: 1024px) {
                    .news-layout {
                        grid-template-columns: 2.5fr 1fr !important;
                    }
                }
                .image-upload-wrap {
                    border: 2px dashed rgba(74, 124, 89, 0.3);
                    border-radius: var(--radius-md);
                    padding: var(--space-lg);
                    text-align: center;
                    cursor: pointer;
                    background: rgba(250, 246, 240, 0.3);
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }
                .image-upload-wrap:hover {
                    background: rgba(74, 124, 89, 0.05);
                    border-color: var(--color-forest);
                }
                .image-upload-wrap img {
                    max-width: 100%;
                    max-height: 250px;
                    border-radius: var(--radius-sm);
                    display: none;
                    margin: 0 auto;
                }
                .upload-placeholder {
                    color: var(--color-charcoal);
                    opacity: 0.6;
                }
                .upload-placeholder .material-symbols-outlined {
                    font-size: 3rem;
                    color: var(--color-forest);
                    opacity: 0.8;
                    margin-bottom: 10px;
                }
            </style>
            
            <div class="news-layout" style="display: grid; grid-template-columns: 1fr; gap: var(--space-xl);">
                
                {{-- KOLOM KIRI: Konten Utama --}}
                <div class="main-content" style="display: flex; flex-direction: column; gap: var(--space-lg);">
                    <div class="admin-card" style="margin: 0;">
                        <h2 style="font-size: 1.25rem; margin-top: 0; margin-bottom: var(--space-md); border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px;">Informasi Utama</h2>
                        
                        <div class="form-group">
                            <label for="title">Judul Berita / Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Masukkan judul yang menarik...">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="excerpt">Ringkasan (Excerpt) <small class="text-muted">(Opsional, maks 500 karakter)</small></label>
                            <textarea name="excerpt" id="excerpt" rows="2" class="form-textarea @error('excerpt') is-invalid @enderror" placeholder="Tuliskan intisari berita di sini...">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="content">Isi Berita <span class="text-danger">*</span></label>
                            <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                            <div id="editor-container" style="min-height: 500px; padding-bottom: 20px; font-size: 1.05rem;">{!! old('content') !!}</div>
                            @error('content')
                                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Sidebar / Metadata --}}
                <div class="sidebar" style="display: flex; flex-direction: column; gap: var(--space-lg);">
                    
                    <!-- Publish Status & Submit Button Card -->
                    <div class="admin-card" style="margin: 0; position: sticky; top: 20px;">
                        <h3 style="font-size: 1.1rem; margin-top: 0; margin-bottom: var(--space-md);">Aksi</h3>
                        
                        <div class="form-group">
                            <label class="custom-switch" style="display: flex; align-items: center; gap: 10px; cursor: pointer; background: rgba(74, 124, 89, 0.05); padding: 12px; border-radius: var(--radius-sm);">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="width: auto;">
                                <span style="font-weight: 500;">Langsung Tayangkan (Publish)</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="width: 100%; justify-content: center; padding: 12px;">
                            <span class="material-symbols-outlined">save</span> Simpan Berita
                        </button>
                    </div>

                    <!-- Cover Image Card -->
                    <div class="admin-card" style="margin: 0;">
                        <h3 style="font-size: 1.1rem; margin-top: 0; margin-bottom: var(--space-md);">Foto Sampul <span class="text-danger">*</span></h3>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <input type="file" name="image" id="image" class="form-input @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" required style="display: none;" onchange="previewImage(this, 'preview-image')">
                            
                            <div class="image-upload-wrap" onclick="document.getElementById('image').click()">
                                <div class="upload-placeholder" id="upload-placeholder">
                                    <span class="material-symbols-outlined">add_photo_alternate</span>
                                    <p style="margin: 0; font-size: 0.9rem;">Klik untuk mengunggah foto<br><small>(JPG, PNG, WEBP - Max 5MB)</small></p>
                                </div>
                                <img id="preview-image" src="#" alt="Preview">
                            </div>
                            
                            @error('image')
                                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Metadata Card -->
                    <div class="admin-card" style="margin: 0;">
                        <h3 style="font-size: 1.1rem; margin-top: 0; margin-bottom: var(--space-md);">Pengaturan Tambahan</h3>
                        
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Tag Berita</label>
                            <input name="tags" id="tags" class="form-input @error('tags') is-invalid @enderror" placeholder="Contoh: inovasi, lingkungan" value="{{ old('tags') }}">
                            <small class="text-muted d-block mt-1">Tekan Enter untuk menambah tag</small>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="event_date">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" id="event_date" class="form-input @error('event_date') is-invalid @enderror" value="{{ old('event_date', date('Y-m-d')) }}" required>
                            @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="location">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-input @error('location') is-invalid @enderror" value="{{ old('location') }}" required placeholder="Contoh: Balai Desa Rowosari">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Quill CSS -->
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
    
    <style>
        /* Override Quill styles to match theme */
        .ql-toolbar.ql-snow {
            border: 1px solid rgba(74, 124, 89, 0.2) !important;
            border-top-left-radius: var(--radius-sm);
            border-top-right-radius: var(--radius-sm);
            background: rgba(250, 246, 240, 0.8);
            font-family: var(--font-body);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .ql-container.ql-snow {
            border: 1px solid rgba(74, 124, 89, 0.2) !important;
            border-bottom-left-radius: var(--radius-sm);
            border-bottom-right-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 1.05rem;
            background: #fff;
        }
        .ql-editor {
            line-height: 1.8;
            padding: 1.5rem;
        }
        .ql-snow .ql-picker.ql-header .ql-picker-label::before,
        .ql-snow .ql-picker.ql-header .ql-picker-item::before {
            font-family: var(--font-body);
        }
        
        /* Tagify fixes */
        .tagify {
            --tags-border-color: rgba(74, 124, 89, 0.2);
            --tags-hover-border-color: var(--color-forest);
            --tags-focus-border-color: var(--color-forest);
            border-radius: var(--radius-sm);
            background: #fff;
        }
    </style>

    <!-- Quill JS -->
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <!-- Tagify CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tagify safely
            try {
                var inputTags = document.querySelector('#tags');
                if (typeof Tagify !== 'undefined') {
                    new Tagify(inputTags);
                } else {
                    console.warn("Tagify library is not loaded properly.");
                }
            } catch (e) {
                console.error("Error initializing Tagify:", e);
            }

            // Initialize Quill safely
            try {
                var quill = new Quill('#editor-container', {
                    theme: 'snow',
                    placeholder: 'Tulis isi berita yang komprehensif di sini...',
                    modules: {
                        toolbar: {
                            container: [
                                [{ 'header': [2, 3, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'align': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['blockquote', 'link', 'image'],
                                ['clean']
                            ],
                            handlers: {
                                image: imageHandler
                            }
                        }
                    }
                });

                var form = document.getElementById('newsForm');
                var contentInput = document.getElementById('content');

                form.onsubmit = function() {
                    var html = quill.root.innerHTML;
                    if(html === '<p><br></p>') html = '';
                    contentInput.value = html;
                };

                function imageHandler() {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/jpeg, image/png, image/webp');
                    input.click();

                    input.onchange = function() {
                        var file = input.files[0];
                        if (!file) return;

                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ukuran gambar terlalu besar. Maksimal 5MB.');
                            return;
                        }

                        var formData = new FormData();
                        formData.append('image', file);
                        
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        var submitBtn = document.getElementById('submitBtn');
                        var originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="material-symbols-outlined" style="animation: spin 1s linear infinite;">refresh</span> Mengunggah...';

                        fetch('{{ route($routePrefix . "news.upload-image") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message || 'Upload gagal (HTTP ' + response.status + ')');
                                });
                            }
                            return response.json();
                        })
                        .then(result => {
                            if (result.url) {
                                var range = quill.getSelection();
                                var index = range ? range.index : quill.getLength();
                                quill.insertEmbed(index, 'image', result.url);
                            }
                        })
                        .catch(error => {
                            console.error('Error uploading image:', error);
                            alert('Gagal mengunggah gambar: ' + error.message);
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                    };
                }
            } catch (e) {
                console.error("Error initializing Quill:", e);
                document.getElementById('editor-container').innerHTML = '<div class="alert alert-danger">Gagal memuat text editor. Silakan muat ulang halaman.</div>';
            }
        });

        function previewImage(input, imgId) {
            var img = document.getElementById(imgId);
            var placeholder = document.getElementById('upload-placeholder');
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                    if(placeholder) placeholder.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                img.style.display = 'none';
                if(placeholder) placeholder.style.display = 'block';
            }
        }
        
        // Add CSS for spinning icon
        var style = document.createElement('style');
        style.type = 'text/css';
        style.innerHTML = '@keyframes spin { 100% { transform: rotate(360deg); } }';
        document.getElementsByTagName('head')[0].appendChild(style);
    </script>
@endsection
