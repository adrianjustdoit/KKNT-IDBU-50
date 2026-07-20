@extends('layouts.admin')

@section('title', 'Manajemen Berita Harian')

@section('content')
    <div class="admin-header">
        <h1>Berita Harian</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <span class="material-symbols-outlined">add</span> Tambah Berita
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__header" style="display: flex; justify-content: space-between; align-items: center; gap: var(--space-md); flex-wrap: wrap;">
            <h2 class="admin-card__title">Daftar Berita</h2>
            
            <form action="{{ route('admin.news.index') }}" method="GET" style="display: flex; gap: var(--space-sm);">
                <select name="status" class="form-control" style="width: auto;">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Tayang</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <input type="text" name="q" class="form-control" placeholder="Cari judul..." value="{{ request('q') }}" style="width: 250px;">
                <button type="submit" class="btn btn-outline">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="80">Foto</th>
                        <th>Judul & Tanggal Kegiatan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        <tr>
                            <td>
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: var(--radius-sm);">
                                @else
                                    <div style="width: 60px; height: 40px; background: var(--color-sand); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
                                        <span class="material-symbols-outlined" style="color: var(--color-wood); font-size: 1.2rem;">image</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--color-forest-dark);">{{ $item->title }}</div>
                                <div style="font-size: 0.85rem; color: var(--color-slate); margin-top: 4px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                                    <span>
                                        <span class="material-symbols-outlined" style="font-size: 0.9rem; vertical-align: middle;">calendar_today</span> 
                                        {{ \Carbon\Carbon::parse($item->event_date)->isoFormat('D MMMM Y') }}
                                    </span>
                                    <span>
                                        <span class="material-symbols-outlined" style="font-size: 0.9rem; vertical-align: middle;">location_on</span>
                                        {{ $item->location }}
                                    </span>
                                    @if($item->category)
                                        <span class="status-badge" style="background: var(--color-amber-light); color: var(--color-charcoal); padding: 2px 8px;">
                                            <span class="material-symbols-outlined" style="font-size: 0.8rem; vertical-align: middle;">folder</span> {{ $item->category->name }}
                                        </span>
                                    @endif
                                </div>
                                @if($item->tags->count() > 0)
                                    <div style="margin-top: 6px; display: flex; gap: 4px; flex-wrap: wrap;">
                                        @foreach($item->tags as $tag)
                                            <span style="font-size: 0.75rem; background: var(--color-light-gray); padding: 2px 6px; border-radius: 4px; color: var(--color-slate);">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->is_published)
                                    <span class="status-badge status-badge--success">Tayang</span>
                                @else
                                    <span class="status-badge status-badge--warning">Draft</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn-icon" title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-danger" title="Hapus">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding: var(--space-xl) 0;">
                                <div style="color: var(--color-wood);">
                                    <span class="material-symbols-outlined" style="font-size: 3rem; margin-bottom: 10px;">newspaper</span>
                                    <p>Belum ada berita. Silakan tambahkan berita baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($news->hasPages())
            <div style="margin-top: var(--space-lg);">
                {{ $news->links() }}
            </div>
        @endif
    </div>

    <style>
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-badge--success {
            background: var(--color-forest-100);
            color: var(--color-forest);
        }
        .status-badge--warning {
            background: rgba(230, 126, 34, 0.1);
            color: var(--color-warning);
        }
    </style>
@endsection
