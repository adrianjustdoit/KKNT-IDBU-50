@extends('layouts.admin')

@section('title', 'Manajemen Struktur Organisasi')

@section('content')
    <div class="admin-header">
        <h1>Struktur Organisasi</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                <span class="material-symbols-outlined">add</span>
                Tambah Anggota
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-card">
        @if($members->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Divisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                            <tr>
                                <td>
                                    @if($member->image_path)
                                        <img src="{{ asset('storage/' . $member->image_path) }}" alt="{{ $member->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center;">
                                            <span class="material-symbols-outlined" style="color: #999;">person</span>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $member->name }}</strong></td>
                                <td>
                                    <span class="badge badge-info">{{ $member->role }}</span>
                                </td>
                                <td>
                                    @if($member->division)
                                        <span class="badge badge-warning">{{ $member->division }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('admin.members.edit', $member) }}" class="btn-icon btn-icon--primary" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        
                                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="inline-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon--danger" title="Hapus">
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
            <div class="empty-state">
                <span class="material-symbols-outlined empty-state__icon">groups</span>
                <h3>Belum ada anggota</h3>
                <p>Mulai tambahkan DPL atau anggota KKN untuk menyusun struktur organisasi.</p>
                <a href="{{ route('admin.members.create') }}" class="btn btn-primary" style="margin-top: var(--space-md);">
                    Tambah Anggota Pertama
                </a>
            </div>
        @endif
    </div>
@endsection
