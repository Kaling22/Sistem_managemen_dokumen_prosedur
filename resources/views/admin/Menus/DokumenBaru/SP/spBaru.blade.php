@extends ('layouts.main')
@section('container')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">DATA PENGAJUAN DOKUMEN</h5>
    @if(in_array(Auth::user()->role, [0, 1]))
      <a href="{{ route('dataSpBaru.create') }}" class="btn btn-primary">
          Tambah Dokumen Baru
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
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      @foreach ($spBaru as $index => $item)
        <tr>
            <td><strong>{{ $index + 1 }}</strong></td>
            <td>{{ $item->no_dokumen }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->jenis_doc }}</td>
            <td>{{ $item->departemen }}</td>
            <td>
                @if($item->file)
                    @php
                        // Logika cerdas menentukan folder
                        $folder = ($item->status_doc == 'Active') ? 'sp' : 'sp_pending';
                    @endphp
                    <a href="{{ asset('storage/' . $folder . '/' . $item->file) }}" target="_blank" class="btn btn-sm btn-link">
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
              @elseif(in_array(Auth::user()->role, [3,4]) && Auth::user()->nama == $item->DHdanSH && $item->status != 'Rejected')
                  <button type="button" onclick="openApprovalModal({{ $item->id }})" class="btn btn-warning btn-sm">
                      Need to Approve
                  </button>
              @else
                  <span class="badge bg-secondary">Waiting</span>
              @endif
            </td>

            {{-- Kolom Status Dokumen --}}
            <td>
               @if($item->status_doc == 'Rejected')
                  <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" 
                        title="Alasan: {{ $item->DHdanSHFeedback }}">
                      Rejected
                  </span>
               @elseif($item->status_doc == 'Approved')
                  <span class="badge bg-success">Complete</span>
               @else
                  <span class="badge bg-primary">{{ $item->status_doc ?? 'Pending' }}</span>
               @endif
            </td>

            <td>
              <div class="d-flex gap-1">
                @if(in_array(Auth::user()->role, [0,1]))
                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                    action="{{ route('dataSpBaru.destroy', $item->id) }}" method="POST">
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

<div class="modal fade" id="modalApproveDH" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dokumen.approve.dhshsp') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="doc_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approval Section/Dept Head</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Feedback / Alasan</label>
                        <textarea class="form-control" name="feedback" rows="3" placeholder="Opsional jika approve, wajib jika reject"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openApprovalModal(id) {
        document.getElementById('doc_id').value = id;
        var myModal = new bootstrap.Modal(document.getElementById('modalApproveDH'));
        myModal.show();
    }
</script>

@endsection