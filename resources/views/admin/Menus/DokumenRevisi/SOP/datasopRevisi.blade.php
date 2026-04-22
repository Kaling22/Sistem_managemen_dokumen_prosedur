@extends ('layouts.main')
@section('container')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">DATA PENGAJUAN DOKUMEN (REVISI)</h5>
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
          <th>Feedback</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      @foreach ($sopRevisi as $index => $item)
        <tr>
            <td><strong>{{ $index + 1 }}</strong></td>
            <td>{{ $item->no_dokumen }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->jenis_doc }}</td>
            <td>{{ $item->departemen }}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/sop_revisi_pending/'.$item->file) }}" target="_blank">
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
              @elseif(in_array(Auth::user()->role, [3,4]) && Auth::user()->nama == $item->DHdanSH && $item->status_doc != 'Rejected')
                  {{-- Tambahkan parameter status_doc di sini --}}
                  <button type="button" onclick="openApprovalModal({{ $item->id }}, '{{ $item->status_doc }}')" class="btn btn-warning btn-sm">
                      Need to Approve
                  </button>
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
              @elseif(Auth::user()->role == 2 && Auth::user()->nama == $item->PJO && $item->DHdanSHApprove == 'approved' && $item->status_doc != 'Rejected')
                  {{-- Tambahkan parameter status_doc di sini --}}
                  <button type="button" onclick="openApprovalPJOModal({{ $item->id }}, '{{ $item->status_doc }}')" class="btn btn-warning btn-sm">
                      Need to Approve
                  </button>
              @else
                <span class="badge bg-secondary">Waiting</span>
              @endif
            </td>

            {{-- Kolom Status Dokumen --}}
            <td>
               @if($item->status_doc == 'Rejected')
                  <span class="badge bg-danger">Rejected</span>
               @elseif($item->status_doc == 'Approved')
                  <span class="badge bg-success">Complete</span>
               @else
                  <span class="badge bg-primary">{{ $item->status_doc ?? 'Pending' }}</span>
               @endif
            </td>
            
            {{-- Kolom Feedback --}}
            <td>
                @if($item->DHdanSHFeedback || $item->PJOFeedback)
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="openFeedbackModal('{{ $item->DHdanSHFeedback ?? '-' }}', '{{ $item->PJOFeedback ?? '-' }}')">
                        <i class="bx bx-message-dots"></i> Lihat Feedback
                    </button>
                @else
                    <span class="text-muted small">No Feedback</span>
                @endif
            </td>

            <td>
              <div class="d-flex gap-1">
                @if(in_array(Auth::user()->role, [0,1]))
                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                    action="{{ route('dataSopRevisi.destroy', $item->id) }}" method="POST">
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

{{-- Modal DH/SH --}}
<div class="modal fade" id="modalApproveDH" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('revisi.sop.approve.dhsh') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="doc_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approval Section/Dept Head (Revisi)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Feedback / Alasan</label>
                        <textarea class="form-control" name="feedback" rows="3" placeholder="Wajib jika reject"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                    {{-- Tambahkan id untuk kontrol JS --}}
                    <button type="submit" name="action" value="approve" id="btn-approve-dh" class="btn btn-success">Approve</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal PJO --}}
<div class="modal fade" id="modalApprovePJO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('revisi.sop.approve.pjo') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="doc_id_pjo">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Final Approval PJO (Revisi)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Feedback / Alasan PJO</label>
                        <textarea class="form-control" name="feedback" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                    {{-- Tambahkan id untuk kontrol JS --}}
                    <button type="submit" name="action" value="approve" id="btn-approve-pjo" class="btn btn-success">Approve & Publish</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal View Feedback --}}
<div class="modal fade" id="modalViewFeedback" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold text-primary">Feedback DH/SH:</label>
                    <div id="view_feedback_dhsh" class="p-2 border rounded bg-light" style="min-height: 50px;"></div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="fw-bold text-primary">Feedback PJO:</label>
                    <div id="view_feedback_pjo" class="p-2 border rounded bg-light" style="min-height: 50px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Approval DH/SH
    function openApprovalModal(id, status) {
        document.getElementById('doc_id').value = id;
        let btn = document.getElementById('btn-approve-dh');
        
        if(status === 'Rejected') {
            btn.disabled = true;
            btn.innerText = 'Cannot Approve (Rejected)';
        } else {
            btn.disabled = false;
            btn.innerText = 'Approve';
        }
        
        var myModal = new bootstrap.Modal(document.getElementById('modalApproveDH'));
        myModal.show();
    }

    // Fungsi Approval PJO
    function openApprovalPJOModal(id, status) {
        document.getElementById('doc_id_pjo').value = id;
        let btn = document.getElementById('btn-approve-pjo');

        if(status === 'Rejected') {
            btn.disabled = true;
            btn.innerText = 'Cannot Approve (Rejected)';
        } else {
            btn.disabled = false;
            btn.innerText = 'Approve & Publish';
        }

        var myModal = new bootstrap.Modal(document.getElementById('modalApprovePJO'));
        myModal.show();
    }

    // Fungsi View Feedback
    function openFeedbackModal(dhshFeedback, pjoFeedback) {
        document.getElementById('view_feedback_dhsh').innerText = dhshFeedback;
        document.getElementById('view_feedback_pjo').innerText = pjoFeedback;
        var myModal = new bootstrap.Modal(document.getElementById('modalViewFeedback'));
        myModal.show();
    }
</script>
@endsection