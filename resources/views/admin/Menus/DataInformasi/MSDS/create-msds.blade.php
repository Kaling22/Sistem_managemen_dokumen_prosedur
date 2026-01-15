@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data MSDS Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataMsds.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">No MSDS</label>
            <input type="text" class="form-control" name="no_dokumen" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul MSDS</label>
            <input type="text" class="form-control" name="judul" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF MSDS</label>
            <input type="file" class="form-control" name="file" accept="application/pdf"  required/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    </div>
</div>
@endsection