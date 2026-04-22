@extends('layouts.main')

@section('container')

<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Anggota Baru</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('auth.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">NRP</label>
                    <input type="text" class="form-control" name="nrp" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Anggota</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kontak</label>
                    <input type="text" class="form-control" name="kontak">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Departemen</label>
                    <select name="departemen" class="form-select" required>
                        <option value="">-- Pilih Departemen --</option>
                        <option value="Produksi">Produksi</option>
                        <option value="ICTMD">ICTMD</option>
                        <option value="HCGA">HCGA</option>
                        <option value="Engineering">Engineering</option>
                        <option value="FALOG">FALOG</option>
                        <option value="Plant">Plant</option>
                        <option value="SHE">SHE</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="0">Admin</option>
                        <option value="1">DOCO</option>
                        <option value="2">PJO</option>
                        <option value="3">Department Head</option>
                        <option value="4">Section Head</option>
                        <option value="5">Group Leader</option>
                        <option value="6">Non Staf</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>


@endsection
