@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
    <div class="admin-header">
        <h1>Kelola Produk</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined">add</span>
                Tambah Produk
            </a>
        </div>
    </div>

    <div class="admin-card">
        @if($products->count() > 0)
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
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
                                <td>
                                    <div class="admin-table__actions">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-dark btn-sm">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center;padding:var(--space-3xl);color:var(--color-muted);">
                <span class="material-symbols-outlined" style="font-size:3rem;">inventory_2</span>
                <p style="margin-top:var(--space-md);">Belum ada produk. Mulai tambahkan produk pertamamu!</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="margin-top:var(--space-lg);">
                    <span class="material-symbols-outlined">add</span>
                    Tambah Produk
                </a>
            </div>
        @endif
    </div>
@endsection
