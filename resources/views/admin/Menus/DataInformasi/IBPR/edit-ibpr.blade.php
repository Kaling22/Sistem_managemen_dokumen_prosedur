@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data IBPR</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataIbpr.update', $ibpr->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No IBPR</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$ibpr->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul IBPR</label>
            <input type="text" class="form-control" name="judul" value="{{$ibpr->judul}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi" value="{{$ibpr->revisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi" value="{{$ibpr->edisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Efektif</label>
            <input type="date" class="form-control" name="tanggal_efektif" value="{{$ibpr->tanggal_efektif}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF IBPR</label>
            <input type="file" class="form-control" name="file" accept="application/pdf"  required/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection