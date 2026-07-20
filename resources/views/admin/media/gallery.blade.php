@extends('layouts.admin')

@section('title', 'Galeri Foto')

@section('content')
    <div class="admin-header">
        <h1>Galeri Foto</h1>
    </div>

    {{-- Upload new photos --}}
    <div class="admin-card" style="margin-bottom:var(--space-xl);">
        <h3 style="margin-bottom:var(--space-lg);">Upload Foto Baru</h3>
        <form action="{{ route('admin.media.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">Judul / Keterangan (opsional)</label>
                <input type="text" id="title" name="title" class="form-input"
                       placeholder="Contoh: Pelatihan pemilahan sampah">
            </div>

            <div class="form-group">
                <div class="image-upload" onclick="document.getElementById('photos').click()">
                    <div class="image-upload__icon">
                        <span class="material-symbols-outlined">cloud_upload</span>
                    </div>
                    <p class="image-upload__text">Klik untuk upload foto (max 2MB, bisa pilih banyak)</p>
                </div>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*" style="display:none;" required>
                <div id="photoPreview" class="image-preview-grid"></div>
                @error('photos.*') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">upload</span>
                    Upload Foto
                </button>
            </div>
        </form>
    </div>

    {{-- Existing gallery --}}
    <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--space-lg);">
            <h3 style="margin:0;">Foto Tersimpan ({{ $photos->count() }})</h3>
            
            @if($photos->count() > 0)
                <form action="{{ route('admin.media.gallery.bulk-destroy') }}" method="POST" id="bulkDeleteForm" onsubmit="return confirm('Hapus foto yang dipilih?')">
                    @csrf
                    <button type="submit" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                        <span class="material-symbols-outlined">delete</span>
                        Hapus Terpilih
                    </button>
                </form>
            @endif
        </div>

        @if($photos->count() > 0)
            <div class="gallery-admin-grid">
                @foreach($photos as $photo)
                    <div class="gallery-admin-item" style="position:relative;">
                        <input type="checkbox" name="photo_ids[]" value="{{ $photo->id }}" form="bulkDeleteForm" class="photo-checkbox" style="position:absolute; top:10px; left:10px; z-index:10; transform:scale(1.5); cursor:pointer;">
                        
                        <img src="{{ asset('storage/' . $photo->image_path) }}"
                             alt="{{ $photo->title ?? 'Gallery photo' }}">
                        
                        <div class="gallery-admin-item__actions">
                            <form action="{{ route('admin.media.gallery.destroy', $photo) }}" method="POST"
                                  onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus">
                                    <span class="material-symbols-outlined" style="font-size:1rem;">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:var(--space-2xl);color:var(--color-muted);">
                <span class="material-symbols-outlined" style="font-size:2.5rem;">photo_library</span>
                <p style="margin-top:var(--space-sm);">Belum ada foto di galeri.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.initImageUpload) window.initImageUpload('photos', 'photoPreview');

            // Bulk Delete Logic
            const checkboxes = document.querySelectorAll('.photo-checkbox');
            const bulkBtn = document.getElementById('bulkDeleteBtn');

            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    const anyChecked = Array.from(checkboxes).some(c => c.checked);
                    bulkBtn.disabled = !anyChecked;
                });
            });
        });
    </script>
@endsection
