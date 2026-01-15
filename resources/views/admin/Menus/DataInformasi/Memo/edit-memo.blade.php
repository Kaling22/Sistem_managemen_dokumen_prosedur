@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data Memo External</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataMemo.update', $memos->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No Memo</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$memos->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul Memo</label>
            <input type="text" class="form-control" name="judul" value="{{$memos->judul}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload PDF Memo</label>
            <input type="file" class="form-control" name="file" accept="application/pdf"  required/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection