@extends ('layouts.main')
@section('container')
<div class="col-xl">
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Kebijakan Perusahaan</h5>
    </div>
    <div class="card-body">
        <form action="{{route('dataKebijakan.update', $kebijakan->id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">No Kebijakan</label>
            <input type="text" class="form-control" name="no_dokumen" value="{{$kebijakan->no_dokumen}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul Kebijakan</label>
            <input type="text" class="form-control" name="judul_kebijakan" value="{{$kebijakan->judul}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Image Kebijakan</label>
            <input type="file" class="form-control" name="file_kebijakan" accept="image/*"  required/>
            <a> jpg,jpeg,png Max 20Mb </a>
        </div>
        <div class="mb-3">
            <label class="form-label">Edisi</label>
            <input type="text" class="form-control" name="edisi" value="{{$kebijakan->edisi}}" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Revisi</label>
            <input type="text" class="form-control" name="revisi" value="{{$kebijakan->revisi}}" required/>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>
@endsection