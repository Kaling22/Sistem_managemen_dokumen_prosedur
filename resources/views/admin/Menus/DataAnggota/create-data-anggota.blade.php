@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Anggota Baru</h5>
        </div>
            <div class="card-body">
                <form action="{{route('auth.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">NRP</label>
                    <input type="text" class="form-control" name="nrp" required/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Anggota</label>
                    <input type="text" class="form-control" name="nama"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kontak</label>
                    <input type="text" class="form-control" name="kontak"/>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="text" class="form-control" name="password"/>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection