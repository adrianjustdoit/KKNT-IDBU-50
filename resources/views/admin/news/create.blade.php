@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
    <div class="admin-header">
        <h1>Tambah Berita</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline">
                <span class="material-symbols-outlined">arrow_back</span> Kembali
            </a>
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
            @csrf

            <div class="form-group">
                <label for="title">Judul Berita / Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="excerpt">Ringkasan (Excerpt) <small class="text-muted">(Opsional, maksimal 500 karakter)</small></label>
                <textarea name="excerpt" id="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt') }}</textarea>
                @error('excerpt')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display: flex; gap: var(--space-xl); flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <label for="event_date">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" id="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', date('Y-m-d')) }}" required>
                    @error('event_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label for="location">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required placeholder="Contoh: Balai Desa Rowosari">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="display: flex; gap: var(--space-xl); flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <label for="category_id">Kategori</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label for="tags">Tag Berita <small class="text-muted">(Tekan Enter untuk menambah)</small></label>
                    <input name="tags" id="tags" class="form-control @error('tags') is-invalid @enderror" placeholder="Contoh: inovasi, lingkungan" value="{{ old('tags') }}">
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="image">Foto Sampul <span class="text-danger">*</span></label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" required onchange="previewImage(this, 'preview-image')">
                <div class="mt-2">
                    <img id="preview-image" src="#" alt="Preview" style="display: none; max-width: 200px; border-radius: var(--radius-sm);">
                </div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 3rem;">
                <label for="content">Isi Berita <span class="text-danger">*</span></label>
                <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                <div id="editor-container" style="min-height: 400px; padding-bottom: 20px;">{!! old('content') !!}</div>
                @error('content')
                    <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="custom-switch" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="width: auto;">
                    <span>Langsung Tayangkan (Publish)</span>
                </label>
            </div>

            <div class="form-actions" style="margin-top: var(--space-xl); padding-top: var(--space-lg); border-top: 1px solid rgba(0,0,0,0.05);">
                <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Berita</button>
            </div>
        </form>
    </div>

    <!-- Quill CSS -->
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
    
    <style>
        /* Override Quill styles to match theme */
        .ql-toolbar.ql-snow {
            border: 1px solid rgba(74, 124, 89, 0.2);
            border-top-left-radius: var(--radius-sm);
            border-top-right-radius: var(--radius-sm);
            background: rgba(250, 246, 240, 0.5);
            font-family: var(--font-body);
        }
        .ql-container.ql-snow {
            border: 1px solid rgba(74, 124, 89, 0.2);
            border-bottom-left-radius: var(--radius-sm);
            border-bottom-right-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 1rem;
        }
        .ql-editor {
            line-height: 1.6;
        }
        .ql-snow .ql-picker.ql-header .ql-picker-label::before,
        .ql-snow .ql-picker.ql-header .ql-picker-item::before {
            font-family: var(--font-body);
        }
    </style>

    <!-- Quill JS -->
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <!-- Tagify CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tagify
            var inputTags = document.querySelector('#tags');
            new Tagify(inputTags);

            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Tulis isi berita di sini...',
                modules: {
                    toolbar: {
                        container: [
                            [{ 'header': [2, 3, false] }],
                            ['bold', 'italic', 'underline'],
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
                // Populate hidden field on submit
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

                    // Client-side file size check (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran gambar terlalu besar. Maksimal 5MB.');
                        return;
                    }

                    var formData = new FormData();
                    formData.append('image', file);
                    
                    // Setup CSRF token for fetch
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Disable submit button during upload
                    var submitBtn = document.getElementById('submitBtn');
                    var originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Mengunggah gambar...';

                    fetch('{{ route("admin.news.upload-image") }}', {
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
                        submitBtn.textContent = originalText;
                    });
                };
            }
        });

        function previewImage(input, imgId) {
            var img = document.getElementById(imgId);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                img.style.display = 'none';
            }
        }
    </script>
@endsection
