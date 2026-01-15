@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data FORMULIR KERJA SHE</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataFkShe.update', $fk->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No FORMULIR KERJA</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$fk->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul FORMULIR KERJA</label>
            <input type="text" class="form-control" name="judul_fk" value="{{$fk->judul_fk}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF FORMULIR KERJA</label>
            <input type="file" class="form-control" name="file_fk" accept="application/pdf"  required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi" value="{{$fk->edisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi" value="{{$fk->revisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Efektif</label>
            <input type="date" class="form-control" name="tanggal_efektif" value="{{$fk->tanggal_efektif}}" required/>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection