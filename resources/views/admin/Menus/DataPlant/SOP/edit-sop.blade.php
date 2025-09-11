@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data SOP PLANT</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataSopPlant.update', $sop->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No SOP</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$sop->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul SOP</label>
            <input type="text" class="form-control" name="judul_sop" value="{{$sop->judul_sop}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF SOP</label>
            <input type="file" class="form-control" name="file_sop" accept="application/pdf"  required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi" value="{{$sop->edisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi" value="{{$sop->revisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Efektif</label>
            <input type="date" class="form-control" name="tanggal_efektif" value="{{$sop->tanggal_efektif}}" required/>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection