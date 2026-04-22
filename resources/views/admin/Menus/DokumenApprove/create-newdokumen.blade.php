@extends ('layouts.main')
@section('container')
<style>
    input[readonly],
    textarea[readonly] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }
</style>
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">DOKUMEN BARU</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataDokumenBaru.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">No Dokumen</label>
            <input type="text" class="form-control" name="no_dokumen" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul Dokumen</label>
            <input type="text" class="form-control" name="judul" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Dokumen</label>
            <input type="text" class="form-control" name="jenis_doc" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF</label>
            <input type="file" class="form-control" name="file" accept="application/pdf"  required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Departemen</label>
            <input type="text"
                class="form-control"
                name="departemen"
                value="{{ Auth::user()->departemen }}"
                readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Pembuat</label>
            <input type="text"
                class="form-control"
                name="pembuat"
                value="{{ Auth::user()->nama }}"
                readonly>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    </div>
</div>
@endsection