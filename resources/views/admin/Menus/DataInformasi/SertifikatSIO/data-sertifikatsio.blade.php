@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">Tabel Data Sertifikat dan SIO</h5>
    @if(Auth::user()->role==0)
      <a href="{{route('dataSertifikatSIO.create')}}" type="button" class="btn btn-primary" >
      Tambah Sertifikat dan SIO Baru
    </a>
    @elseif(Auth::user()->role==1)
    @else
      Jenis Akun Tidak Memiliki Akses.
    @endif
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>No Sertifikat/SIO</th>
          <th>Judul</th>
          <th>File</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php $index = 1; ?>
      @foreach ($sertifikatsio as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->no_dokumen}}</td>
            <td>{{$item->judul}}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/sertifikatSIO/'.$item->file) }}" target="_blank">
                        File Dokumen
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>
            @if(Auth::user()->role==0)
            <a href="{{ route('dataSertifikatSIO.edit', $item->id) }}"class="btn btn-sm btn-secondary">Edit</a>
            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                action="{{ route('dataSertifikatSIO.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
            @elseif(Auth::user()->role==1)
                No Access.
            @else
                Jenis Akun Tidak Memiliki Akses.
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection