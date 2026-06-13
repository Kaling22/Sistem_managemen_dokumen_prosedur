@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">Tabel Data JSA SHE</h5>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>No JSA</th>
          <th>Judul</th>
          <th>File</th>
          <th>Edisi</th>
          <th>Revisi</th>
          <th>Tanggal Efektif</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php $index = 1; ?>
      @foreach ($jsa as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->no_dokumen}}</td>
            <td>{{$item->judul_jsa}}</td>
            <td>
                @if($item->file_jsa)
                    <a href="{{ asset('storage/jsa/'.$item->file_jsa) }}" target="_blank">
                        File Dokumen
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>{{$item->edisi}}</td>
            <td>{{$item->revisi}}</td>
            <td>{{$item->tanggal_efektif}}</td>
            <td>
            @if(Auth::user()->role==0)
            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                action="{{ route('dataJsaShe.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
            @else
                <span class="text-muted">-</span>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection