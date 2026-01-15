@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">Tabel Data Link</h5>
    @if(Auth::user()->role==0)
      <a href="{{route('dataLinkProduksi.create')}}" type="button" class="btn btn-primary" >
      Tambah Link Baru
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
          <th>Judul</th>
          <th>Link</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php $index = 1; ?>
      @foreach ($link as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->judul}}</td>
            <td>
                @if($item->link)
                    <a href="{{ $item->link }}" target="_blank">
                        Link Form
                    </a>
                @else
                    <span class="text-muted">Tidak ada link</span>
                @endif
            </td>
            <td>
            @if(Auth::user()->role==0)
            <a href="{{ route('dataLinkProduksi.edit', $item->id) }}"class="btn btn-sm btn-secondary">Edit</a>
            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                action="{{ route('dataLinkProduksi.destroy', $item->id) }}" method="POST">
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