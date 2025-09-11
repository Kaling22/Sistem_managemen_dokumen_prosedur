@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data JSA OPD</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataJsaOpd.update', $jsa->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No JSA</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$jsa->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul JSA</label>
            <input type="text" class="form-control" name="judul_jsa" value="{{$jsa->judul_jsa}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF JSA</label>
            <input type="file" class="form-control" name="file_jsa" accept="application/pdf"  required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi" value="{{$jsa->edisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi" value="{{$jsa->revisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Efektif</label>
            <input type="date" class="form-control" name="tanggal_efektif" value="{{$jsa->tanggal_efektif}}" required/>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection