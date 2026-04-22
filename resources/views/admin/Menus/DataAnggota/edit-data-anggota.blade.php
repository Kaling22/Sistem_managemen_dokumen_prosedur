@extends('layouts.main')

@section('container')

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Anggota</h5>
        </div>
    <div class="card-body">
        <form action="{{ route('auth.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- NRP --}}
            <div class="mb-3">
                <label class="form-label">NRP</label>
                <input
                    type="text"
                    class="form-control"
                    name="nrp"
                    value="{{ old('nrp', $anggota->nrp) }}"
                    required
                >
            </div>

            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label">Nama Anggota</label>
                <input
                    type="text"
                    class="form-control"
                    name="nama"
                    value="{{ old('nama', $anggota->nama) }}"
                    required
                >
            </div>

            {{-- Kontak --}}
            <div class="mb-3">
                <label class="form-label">Kontak</label>
                <input
                    type="text"
                    class="form-control"
                    name="kontak"
                    value="{{ old('kontak', $anggota->kontak) }}"
                >
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    name="email"
                    value="{{ old('email', $anggota->email) }}"
                >
            </div>

            {{-- Departemen --}}
            <div class="mb-3">
                <label class="form-label">Departemen</label>
                <select name="departemen" class="form-select" required>
                    <option value="">-- Pilih Departemen --</option>
                    <option value="Produksi" {{ $anggota->departemen == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                    <option value="ICTMD" {{ $anggota->departemen == 'ICTMD' ? 'selected' : '' }}>ICTMD</option>
                    <option value="HCGA" {{ $anggota->departemen == 'HCGA' ? 'selected' : '' }}>HCGA</option>
                    <option value="Engineering" {{ $anggota->departemen == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                    <option value="FALOG" {{ $anggota->departemen == 'FALOG' ? 'selected' : '' }}>FALOG</option>
                    <option value="Plant" {{ $anggota->departemen == 'Plant' ? 'selected' : '' }}>Plant</option>
                    <option value="SHE" {{ $anggota->departemen == 'SHE' ? 'selected' : '' }}>SHE</option>
                </select>
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="role" class="form-select" required>
                    <option value="0" {{ (int)$anggota->role === 0 ? 'selected' : '' }}>Admin</option>
                    <option value="1" {{ (int)$anggota->role === 1 ? 'selected' : '' }}>DOCO</option>
                    <option value="2" {{ (int)$anggota->role === 2 ? 'selected' : '' }}>PJO</option>
                    <option value="3" {{ (int)$anggota->role === 3 ? 'selected' : '' }}>Department Head</option>
                    <option value="4" {{ (int)$anggota->role === 4 ? 'selected' : '' }}>Section Head</option>
                    <option value="5" {{ (int)$anggota->role === 5 ? 'selected' : '' }}>Group Leader</option>
                    <option value="6" {{ (int)$anggota->role === 6 ? 'selected' : '' }}>Non Staf</option>
                </select>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">
                    Password
                    <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                </label>
                <input
                    type="password"
                    class="form-control"
                    name="password"
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.querySelector('select[name="role"]');
    const signatureWrapper = document.getElementById('signature-wrapper');

    // role yang WAJIB tanda tangan
    const rolesWithSignature = ['2', '3', '4'];

    function toggleSignature() {
        if (rolesWithSignature.includes(roleSelect.value)) {
            signatureWrapper.classList.remove('d-none');
        } else {
            signatureWrapper.classList.add('d-none');
        }
    }

    // run saat load pertama
    toggleSignature();

    // run saat role berubah
    roleSelect.addEventListener('change', toggleSignature);
});
</script>

@endsection
