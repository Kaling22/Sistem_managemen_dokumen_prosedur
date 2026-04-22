@extends ('layouts.main')
@section('container')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">DATA PENGAJUAN DOKUMEN SOP</h5>
    @if(in_array(Auth::user()->role, [0, 1]))
      <a href="{{ route('dataDokumenBaru.create') }}" class="btn btn-primary">
          Tambah
      </a>
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
      @foreach ($newDokumen as $index => $item)
        <tr>
            <td><strong>{{ $index + 1 }}</strong></td>
            <td>{{ $item->no_dokumen }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->jenis_doc }}</td>
            <td>{{ $item->departemen }}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/newDokumen/'.$item->file) }}" target="_blank" class="">
                        Lihat PDF
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>{{ $item->pembuat }}</td>
            
            {{-- Kolom Approval DH/SH --}}
            <td>
              @if($item->status == 'Rejected' && $item->DHdanSHApprove == 'rejected')
                  <span class="badge bg-danger">Rejected</span>
              @elseif($item->DHdanSHApprove == 'approved')
                  <span class="badge bg-success">Approved</span>
              @elseif(in_array(Auth::user()->role, [3,4]) && $item->status != 'Rejected')
                  <a href="{{ route('dokumen.approve.view', $item->id) }}" class="btn btn-warning btn-sm">
                      Need to Approve
                  </a>
              @else
                  <span class="badge bg-secondary">Waiting</span>
              @endif
            </td>

            {{-- Kolom Approval PJO --}}
            <td>
              @if($item->status == 'Rejected' && $item->PJOApprove == 'rejected')
                  <span class="badge bg-danger">Rejected</span>
              @elseif($item->PJOApprove == 'approved')
                  <span class="badge bg-success">Approved</span>
              @elseif(Auth::user()->role == 2 && $item->DHdanSHApprove == 'approved' && $item->status != 'Rejected')
                <a href="{{ route('dokumen.approve.view', $item->id) }}" class="btn btn-warning btn-sm">
                    Need to Approve
                </a>
              @else
                <span class="badge bg-secondary">Waiting</span>
              @endif
            </td>

            {{-- Kolom Status Dokumen --}}
            <td>
               @if($item->status == 'Rejected')
                  <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" 
                        title="Alasan: {{ $item->DHdanSHFeedback ?? $item->PJOFeedback }}">
                      Rejected
                  </span>
               @elseif($item->status == 'Approved')
                  <span class="badge bg-success">Approved</span>
               @else
                  <span class="badge bg-primary">{{ $item->status ?? 'Pending' }}</span>
               @endif
            </td>

            <td>
              <div class="d-flex gap-1">
                {{-- Tombol Hapus hanya untuk pembuat --}}
                @if(in_array(Auth::user()->role, [0,1]))
                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                    action="{{ route('dataDokumenBaru.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endif
              </div>
            </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection