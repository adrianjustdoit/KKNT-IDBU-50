@extends('layouts.admin')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="admin-header">
        <h1>Tambah Anggota Struktur Organisasi</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-dark btn-sm">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap *</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="role">Jabatan *</label>
                    <select id="role" name="role" class="form-select" required onchange="toggleDivision()">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach(App\Models\Member::$roles as $role => $hierarchy)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="division">Divisi</label>
                    <select id="division" name="division" class="form-select" disabled>
                        <option value="">-- Tidak ada (BPH/DPL) --</option>
                        @foreach(App\Models\Member::$divisions as $div)
                            <option value="{{ $div }}" {{ old('division') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                    <small style="color: #666; font-size: 0.85rem;">Hanya diisi jika jabatan Kadiv atau Anggota.</small>
                    @error('division') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="image">Foto (Opsional)</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
                <small style="color: #666; font-size: 0.85rem;">Format disarankan: Persegi (1:1), max 2MB. Jika tidak diunggah, akan menggunakan ikon default.</small>
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;gap:var(--space-md);justify-content:flex-end;margin-top:var(--space-xl);">
                <a href="{{ route('admin.members.index') }}" class="btn btn-outline-dark">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Anggota
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
                if (!division.value) {
                    division.required = true;
                }
            } else {
                division.value = '';
                division.disabled = true;
                division.required = false;
            }
        }
        
        // Run on load
        document.addEventListener('DOMContentLoaded', toggleDivision);
    </script>
@endsection
