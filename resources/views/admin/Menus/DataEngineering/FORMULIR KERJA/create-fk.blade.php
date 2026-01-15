@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data FORMULIR KERJA Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataFkEngineering.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">No FORMULIR KERJA</label>
            <input type="text" class="form-control" name="no_dokumen" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul FORMULIR KERJA</label>
            <input type="text" class="form-control" name="judul_fk" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF FORMULIR KERJA</label>
            <input type="file" class="form-control" name="file_fk" accept="application/pdf"  required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi"/>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Efektif</label>
            <input type="date" class="form-control" name="tanggal_efektif"/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    </div>
</div>
@endsection