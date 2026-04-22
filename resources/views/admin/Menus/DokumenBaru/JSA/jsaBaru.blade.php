@extends ('layouts.main')
@section('container')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">DATA PENGAJUAN DOKUMEN</h5>
    @if(in_array(Auth::user()->role, [0, 1]))
      <a href="{{ route('dataJsaBaru.create') }}" class="btn btn-primary">
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
            <th>Departmen</th>
            <th>File</th>
            <th>Pembuat</th>
            <th>Review & Approval</th> {{-- Gabungan Review Pencegahan & Reviewer Approve --}}
            <th>DH/SH Approve</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      @foreach ($jsaBaru as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->no_dokumen }}</td>
            <td>{{ $item->nama_pekerjaan }}</td>
            <td>{{ $item->departemen }}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/jsa_pending/'.$item->file) }}" target="_blank">
                        Lihat PDF
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>{{ $item->dibuat_oleh }}</td>
            {{-- Kolom Review & Approval Gabungan --}}
            <td>
                @if($item->direview_oleh_approve == 'approved')
                    <span class="badge bg-success mb-1 d-block">Approved</span>
                    <a href="{{route('dataJsaBaru.review', $item->id)}}" class="btn btn-xs btn-outline-secondary">Lihat Review</a>
                @elseif($item->direview_oleh_approve == 'rejected')
                    <span class="badge bg-danger mb-1 d-block">Rejected</span>
                    <a href="{{route('dataJsaBaru.review', $item->id)}}" class="btn btn-xs btn-warning">Re-Review</a>
                @elseif(Auth::user()->nama == $item->direview_oleh)
                    <a href="{{route('dataJsaBaru.review', $item->id)}}" class="btn btn-sm btn-primary">
                        <i class="bx bx-edit"></i> Butuh Review
                    </a>
                @else
                    <span class="badge bg-secondary">Waiting Reviewer</span>
                @endif
            </td>

            {{-- Kolom DH/SH --}}
            <td>
                @if($item->disetujui_oleh_approve == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($item->direview_oleh_approve == 'approved' && Auth::user()->nama == $item->disetujui_oleh)
                    <button type="button" onclick="openApprovalModal({{ $item->id }}, '{{ $item->status_doc }}')" class="btn btn-warning btn-sm">
                        Need Action
                    </button>
                @else
                    <span class="badge bg-secondary">Waiting</span>
                @endif
            </td>
            {{-- Kolom Status Dokumen --}}
            <td>
               @if($item->status_doc == 'Rejected')
                  <span class="badge bg-danger">Rejected</span>
               @elseif($item->status_doc == 'Approved' || $item->status_doc == 'Active')
                  <span class="badge bg-success">Complete</span>
               @else
                  <span class="badge bg-primary">{{ $item->status_doc ?? 'Pending' }}</span>
               @endif
            </td>

            {{-- Kolom Feedback --}}
            <td>
                @if($item->direview_oleh_feedback || $item->disetujui_oleh_feedback)
                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                            onclick="openFeedbackModal('{{ $item->direview_oleh_feedback ?? '-' }}', '{{ $item->disetujui_oleh_feedback ?? '-' }}')">
                        <i class="bx bx-message-dots"></i> Lihat Feedback
                    </button>
                @else
                    <span class="text-muted small">No Feedback</span>
                @endif
            </td>

            {{-- Kolom Action --}}
            <td>
              <div class="d-flex gap-1">
                @if(in_array(Auth::user()->role, [0,1]))
                @if($item->direview_oleh_approve == 'rejected')
                    <a href="{{ route('dataJsaBaru.edit', $item->id) }}" class="btn btn-sm btn-warning">
                        <i class="bx bx-edit-alt"></i> Perbaiki
                    </a>
                @endif
                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                    action="{{ route('dataJsaBaru.destroy', $item->id) }}" method="POST">
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

{{-- MODAL APPROVAL REVIEWER --}}
<div class="modal fade" id="modalReviewerApproval" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- <form action="" method="POST"> -->
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="id" id="reviewer_doc_id">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white">Review Dokumen JSA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Feedback Reviewer</label>
                        <textarea class="form-control" name="feedback" rows="3" placeholder="Tambahkan catatan review..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="rejected" class="btn btn-danger">Reject</button>
                    <button type="submit" name="action" value="approved" class="btn btn-success">Approve Review</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL APPROVAL DH/SH --}}
<div class="modal fade" id="modalApproveDH" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dokumen.approve.dhsh') }}" method="POST">
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
                        <textarea class="form-control" name="feedback" rows="3" placeholder="Wajib diisi jika reject"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="rejected" class="btn btn-danger">Reject</button>
                    <button type="submit" name="action" value="approved" id="btn-approve-dh" class="btn btn-success">Approve</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL VIEW FEEDBACK --}}
<div class="modal fade" id="modalViewFeedback" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold text-info">Feedback Reviewer:</label>
                    <div id="view_feedback_reviewer" class="p-2 border rounded bg-light" style="min-height: 50px;"></div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="fw-bold text-primary">Feedback DH/SH:</label>
                    <div id="view_feedback_dhsh" class="p-2 border rounded bg-light" style="min-height: 50px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Approval Reviewer
    function openReviewerApprovalModal(id) {
        document.getElementById('reviewer_doc_id').value = id;
        var myModal = new bootstrap.Modal(document.getElementById('modalReviewerApproval'));
        myModal.show();
    }

    // Fungsi Approval DH/SH
    function openApprovalModal(id, status) {
        document.getElementById('doc_id').value = id;
        let btnApprove = document.getElementById('btn-approve-dh');

        if (status === 'Rejected') {
            btnApprove.disabled = true;
            btnApprove.innerText = 'Cannot Approve (Rejected)';
        } else {
            btnApprove.disabled = false;
            btnApprove.innerText = 'Approve';
        }

        var myModal = new bootstrap.Modal(document.getElementById('modalApproveDH'));
        myModal.show();
    }

    // Fungsi View Feedback
    function openFeedbackModal(reviewerFeedback, dhshFeedback) {
        document.getElementById('view_feedback_reviewer').innerText = reviewerFeedback;
        document.getElementById('view_feedback_dhsh').innerText = dhshFeedback;
        var myModal = new bootstrap.Modal(document.getElementById('modalViewFeedback'));
        myModal.show();
    }
</script>
@endsection