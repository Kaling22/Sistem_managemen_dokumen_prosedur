@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">DATA PENGAJUAN REVISI DOKUMEN</h5>
    @if(in_array(Auth::user()->role, [0, 1]))
      <a href="{{ route('dataDokumenRevisi.create') }}" class="btn btn-primary">
          AJUKAN REVISI DOKUMEN
      </a>
    @else
        Jenis Akun Tidak Memiliki Akses.
    @endif
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>No Dokumen</th>
          <th>Judul</th>
          <th>Jenis Dokumen</th>
          <th>Departmen</th>
          <th>File</th>
          <th>Pembuat</th>
          <th>DH/SH Approve</th>
          <th>PJO Approve</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php $index = 1; ?>
      @foreach ($editDokumen as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->no_dokumen}}</td>
            <td>{{$item->judul}}</td>
            <td>{{$item->jenis_doc}}</td>
            <td>{{$item->departemen}}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/editDokumen/'.$item->file) }}" target="_blank">
                        File Dokumen
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif</td>
            <td>{{$item->pembuat}}</td>
            <td>
              @if(in_array(Auth::user()->role, [3,4]) && $item->DHdanSHApprove != 'approved')
                  <a href="{{ route('dokumen.approve.view', $item->id) }}" 
                    class="btn btn-warning btn-sm">
                      Need to Approve
                  </a>
              @elseif($item->DHdanSHApprove == 'approved')
                  <span class="badge bg-success">Approved</span>
              @else
                  <span class="badge bg-secondary">Waiting</span>
              @endif
            </td>
            <td>
              @if(Auth::user()->role == 2 
                && $item->PJOApprove != 'approved'
                && $item->DHdanSHApprove == 'approved')
                <a href="{{ route('dokumen.approve.view', $item->id) }}" 
                  class="btn btn-warning btn-sm">
                  Need to Approve
                </a>
              @elseif($item->PJOApprove == 'approved')
                <span class="badge bg-success">Approved</span>
              @else
                <span class="badge bg-secondary">Waiting</span>
              @endif
            </td>
            <td>
              <x-status-badge :status="$item->status" />
            </td>
            <td>
              @if(in_array(Auth::user()->role, [0,1]))
              <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                  action="{{ route('dataDokumenBaru.destroy', $item->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
              </form>
              @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection