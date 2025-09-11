@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data IK HCGA</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataIkHcga.update', $ik->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No IK</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$ik->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul IK</label>
            <input type="text" class="form-control" name="judul_ik" value="{{$ik->judul_ik}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF IK</label>
            <input type="file" class="form-control" name="file_ik" accept="application/pdf"  required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi" value="{{$ik->edisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi" value="{{$ik->revisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Efektif</label>
            <input type="date" class="form-control" name="tanggal_efektif" value="{{$ik->tanggal_efektif}}" required/>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection