@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Data Link Produksi</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataLinkProduksi.update', $link->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Judul Link</label>
            <input type="text" class="form-control" name="judul" value="{{$link->judul}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">LINK</label>
            <input type="text" class="form-control" name="link" value="{{$link->link}}" required/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>

@endsection