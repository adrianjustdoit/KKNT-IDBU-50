@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="admin-header">
        <h1>Dashboard</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined">add</span>
                Tambah Produk
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="admin-stats">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon">
                <span class="material-symbols-outlined">inventory_2</span>
            </div>
            <div>
                <div class="admin-stat-card__number">{{ $stats['total_products'] }}</div>
                <div class="admin-stat-card__label">Total Produk</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon" style="background:rgba(45,106,79,0.1);color:#2d6a4f;">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <div class="admin-stat-card__number">{{ $stats['active_products'] }}</div>
                <div class="admin-stat-card__label">Produk Aktif</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon" style="background:rgba(196,166,106,0.15);color:var(--color-amber-dark);">
                <span class="material-symbols-outlined">photo_library</span>
            </div>
            <div>
                <div class="admin-stat-card__number">{{ $stats['gallery_photos'] }}</div>
                <div class="admin-stat-card__label">Foto Galeri</div>
            </div>
        </div>
    </div>

    {{-- Recent Products --}}
    <div class="admin-card">
        <h3 style="margin-bottom:var(--space-lg);">Produk Terbaru</h3>
        @if($recentProducts->count() > 0)
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProducts as $product)
                            <tr>
                                <td>
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="admin-table__thumb">
                                    @else
                                        <div class="admin-table__thumb placeholder-img" style="display:flex;align-items:center;justify-content:center;font-size:1rem;">
                                            <span class="material-symbols-outlined">image</span>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>
                                    <span class="product-card__badge product-card__badge--{{ $product->category }}" style="position:static;">
                                        {{ ucfirst($product->category) }}
                                    </span>
                                </td>
                                <td>{{ $product->formatted_price }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span style="color:var(--color-success);font-weight:600;">● Aktif</span>
                                    @else
                                        <span style="color:var(--color-muted);font-weight:600;">● Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center;padding:var(--space-2xl);color:var(--color-muted);">
                <span class="material-symbols-outlined" style="font-size:2rem;">inventory_2</span>
                <p style="margin-top:var(--space-sm);">Belum ada produk.</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm" style="margin-top:var(--space-md);">
                    Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>

    {{-- Top News --}}
    <div class="admin-card" style="margin-top: var(--space-xl);">
        <h3 style="margin-bottom:var(--space-lg);">Berita Terpopuler</h3>
        @if(isset($topNews) && $topNews->count() > 0)
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Judul Berita</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Dilihat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topNews as $news)
                            <tr>
                                <td>
                                    @if($news->image_path)
                                        <img src="{{ asset('storage/' . $news->image_path) }}"
                                             alt="{{ $news->title }}"
                                             class="admin-table__thumb" style="object-fit: cover;">
                                    @else
                                        <div class="admin-table__thumb placeholder-img" style="display:flex;align-items:center;justify-content:center;font-size:1rem;">
                                            <span class="material-symbols-outlined">image</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ \Illuminate\Support\Str::limit($news->title, 50) }}</strong>
                                </td>
                                <td>
                                    <span style="background: rgba(45,106,79,0.1); color: var(--color-forest); padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                        {{ $news->category ? $news->category->name : '-' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($news->published_at)->format('d M Y') }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 4px; color: var(--color-forest-dark); font-weight: bold;">
                                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">visibility</span>
                                        {{ number_format($news->view_count, 0, ',', '.') }} kali
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center;padding:var(--space-2xl);color:var(--color-muted);">
                <span class="material-symbols-outlined" style="font-size:2rem;">newspaper</span>
                <p style="margin-top:var(--space-sm);">Belum ada berita yang diterbitkan.</p>
            </div>
        @endif
    </div>
@endsection
