@extends('layouts.admin')

@section('title', 'Edit Anggota')

@section('content')
    <div class="admin-header">
        <h1>Edit Anggota</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-dark btn-sm">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.members.update', $member) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap *</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name', $member->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="role">Jabatan *</label>
                    <select id="role" name="role" class="form-select" required onchange="toggleDivision()">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach(App\Models\Member::$roles as $role => $hierarchy)
                            <option value="{{ $role }}" {{ old('role', $member->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="division">Divisi</label>
                    <select id="division" name="division" class="form-select">
                        <option value="">-- Tidak ada (BPH/DPL) --</option>
                        @foreach(App\Models\Member::$divisions as $div)
                            <option value="{{ $div }}" {{ old('division', $member->division) == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                    <small style="color: #666; font-size: 0.85rem;">Hanya diisi jika jabatan Kadiv atau Anggota.</small>
                    @error('division') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Saat Ini</label>
                @if($member->image_path)
                    <div style="margin-bottom: var(--space-md);">
                        <img src="{{ asset('storage/' . $member->image_path) }}" alt="{{ $member->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                    </div>
                @else
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: var(--space-md);">Belum ada foto.</p>
                @endif
                
                <label class="form-label" for="image">Ganti Foto (Opsional)</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;gap:var(--space-md);justify-content:flex-end;margin-top:var(--space-xl);">
                <a href="{{ route('admin.members.index') }}" class="btn btn-outline-dark">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleDivision() {
            const role = document.getElementById('role').value;
            const division = document.getElementById('division');
            
            if (role === 'Kadiv' || role === 'Anggota') {
                division.disabled = false;
            } else {
                division.value = '';
                division.disabled = true;
            }
        }
        
        // Run on load
        document.addEventListener('DOMContentLoaded', toggleDivision);
    </script>
@endsection
