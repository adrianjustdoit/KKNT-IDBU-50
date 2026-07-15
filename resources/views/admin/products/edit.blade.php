@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="admin-header">
        <h1>Edit Produk</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark btn-sm">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nama Produk *</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name', $product->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="category">Kategori *</label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="kriya" {{ old('category', $product->category) === 'kriya' ? 'selected' : '' }}>✂️ Kriya</option>
                        <option value="organik" {{ old('category', $product->category) === 'organik' ? 'selected' : '' }}>🌱 Organik</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Harga (Rp) *</label>
                    <input type="number" id="price" name="price" class="form-input"
                           value="{{ old('price', $product->price) }}" required min="0">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-textarea">{{ old('description', $product->description) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="shopee_link">Link Shopee</label>
                    <input type="text" id="shopee_link" name="shopee_link" class="form-input"
                           value="{{ old('shopee_link', $product->shopee_link) }}">
                    @error('shopee_link') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="tokopedia_link">Link Tokopedia</label>
                    <input type="text" id="tokopedia_link" name="tokopedia_link" class="form-input"
                           value="{{ old('tokopedia_link', $product->tokopedia_link) }}">
                    @error('tokopedia_link') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    Produk aktif (tampil di katalog)
                </label>
            </div>

            {{-- Existing images --}}
            @if($product->images->count() > 0)
                <div class="form-group">
                    <label class="form-label">Foto Saat Ini</label>
                    <div class="image-preview-grid">
                        @foreach($product->images as $image)
                            <div class="image-preview-item">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product image">
                                <button type="submit" form="delete-image-{{ $image->id }}" class="image-preview-item__delete" onclick="return confirm('Hapus foto ini?')">✕</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Tambah Foto Baru</label>
                <div class="image-upload" onclick="document.getElementById('images').click()">
                    <div class="image-upload__icon">
                        <span class="material-symbols-outlined">cloud_upload</span>
                    </div>
                    <p class="image-upload__text">Klik untuk upload foto tambahan</p>
                </div>
                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display:none;">
                <div id="imagePreview" class="image-preview-grid"></div>
            </div>

            <div style="display:flex;gap:var(--space-md);justify-content:flex-end;margin-top:var(--space-xl);">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @if($product->images->count() > 0)
        @foreach($product->images as $image)
            <form id="delete-image-{{ $image->id }}" action="{{ route('admin.product-image.destroy', $image) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.initImageUpload) window.initImageUpload('images', 'imagePreview');
        });
    </script>
@endsection
