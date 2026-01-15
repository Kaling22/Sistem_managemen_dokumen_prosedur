@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data Sertifikat dan SIO</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataSertifikatSIO.update', $sertifikatsio->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No Sertifikat/SIO</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$sertifikatsio->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul Sertifikat/SIO</label>
            <input type="text" class="form-control" name="judul" value="{{$sertifikatsio->judul}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF Sertifikat/SIO</label>
            <input type="file" class="form-control" name="file" accept="application/pdf"  required/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection