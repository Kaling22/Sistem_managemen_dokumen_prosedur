@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Anggota</h5>
        </div>
        <div class="card-body">
            <form action="{{route('auth.update', $anggota->id )}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">NRP</label>
                <input type="text" class="form-control" name="nrp" value="{{$anggota->nrp}}" required/>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" value="{{$anggota->nama}}"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Kontak</label>
                <input type="text" class="form-control" name="kontak" value="{{$anggota->kontak}}"/>
            </div>
            
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" value="{{$anggota->password}}"/>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        </div>
    </div>
</div>
@endsection