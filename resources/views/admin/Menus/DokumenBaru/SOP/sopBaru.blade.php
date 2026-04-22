@extends ('layouts.main')
@section('container')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">DATA PENGAJUAN DOKUMEN</h5>
    @if(in_array(Auth::user()->role, [0, 1]))
      <a href="{{ route('dataSopBaru.create') }}" class="btn btn-primary">
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
          <th>People Approve</th>
          <th>DH/SH Approve</th>
          <th>PJO Approve</th>
          <th>Status</th>
          <th>Feedback</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      @foreach ($sopBaru as $index => $item)
        <tr>
            <td><strong>{{ $index + 1 }}</strong></td>
            <td>{{ $item->no_dokumen }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->jenis_doc }}</td>
            <td>{{ $item->departemen }}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/sop_pending/'.$item->file) }}" target="_blank">
                        Lihat PDF
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>{{ $item->pembuat }}</td>
            
            {{-- Kolom Approval People (Staf Terkait) --}}
            <td>
                @php
                    $statusPeople = json_decode($item->people_approve, true) ?? [];
                    $userLogin = Auth::user()->nama;
                    $myStatus = $statusPeople[$userLogin] ?? null;
                @endphp
                <div class="d-flex flex-column gap-1">
                    @if(isset($statusPeople[$userLogin]) && $myStatus == 'pending' && $item->status_doc != 'Rejected')
                        <button type="button" onclick="openPeopleApprovalModal({{ $item->id }})" class="btn btn-primary btn-xs mb-1">
                            Approve as Staff
                        </button>
                    @endif

                    @foreach($statusPeople as $nama => $status)
                        <span class="badge {{ $status == 'approved' ? 'bg-success' : ($status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}" 
                              style="font-size: 10px;" data-bs-toggle="tooltip" title="{{ $nama }}">
                            {{ Str::limit($nama, 10) }}: {{ ucfirst($status) }}
                        </span>
                    @endforeach
                </div>
            </td>

            {{-- Kolom Approval DH/SH --}}
            <td>
                @if($item->DHdanSHApprove == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @elseif($item->DHdanSHApprove == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif(in_array(Auth::user()->role, [3,4]) && Auth::user()->nama == $item->DHdanSH && $item->status_doc != 'Rejected')
                    <button type="button" onclick="openApprovalModal({{ $item->id }}, '{{ $item->status_doc }}')" class="btn btn-warning btn-sm">
                        Need to Approve
                    </button>
                @else
                    <span class="badge bg-secondary">Waiting</span>
                @endif
            </td>

            {{-- Kolom Approval PJO --}}
            <td>
                @if($item->PJOApprove == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @elseif($item->PJOApprove == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif(Auth::user()->role == 2 && Auth::user()->nama == $item->PJO && $item->DHdanSHApprove == 'approved' && $item->status_doc != 'Rejected')
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
               @elseif($item->status_doc == 'Approved' || $item->status_doc == 'Active')
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

            {{-- Kolom Action --}}
            <td>
              <div class="d-flex gap-1">
                @if(in_array(Auth::user()->role, [0,1]))
                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                    action="{{ route('dataSopBaru.destroy', $item->id) }}" method="POST">
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

{{-- MODAL APPROVAL PEOPLE (NEW) --}}
<div class="modal fade" id="modalPeopleApproval" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('sop.people.approve') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="people_doc_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Persetujuan Staf</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda menyetujui draf SOP ini untuk diproses lebih lanjut?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="rejected" class="btn btn-danger">Reject</button>
                    <button type="submit" name="action" value="approved" class="btn btn-success">Approve</button>
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
                        <textarea class="form-control" name="feedback" rows="3" placeholder="Opsional jika approve, wajib jika reject"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                    <button type="submit" name="action" value="approve" id="btn-approve-dh" class="btn btn-success">Approve</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL APPROVAL PJO --}}
<div class="modal fade" id="modalApprovePJO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dokumen.approve.pjo') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="doc_id_pjo">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Final Approval PJO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Feedback / Alasan PJO</label>
                        <textarea class="form-control" name="feedback" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                    <button type="submit" name="action" value="approve" id="btn-approve-pjo" class="btn btn-success">Approve & Publish</button>
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
    // Fungsi Approval People (Staf)
    function openPeopleApprovalModal(id) {
        document.getElementById('people_doc_id').value = id;
        var myModal = new bootstrap.Modal(document.getElementById('modalPeopleApproval'));
        myModal.show();
    }

    // Fungsi Approval DH/SH
    function openApprovalModal(id, status) {
        document.getElementById('doc_id').value = id;
        let btnApprove = document.getElementById('btn-approve-dh');

        if (status === 'Rejected') {
            btnApprove.disabled = true;
            btnApprove.classList.add('disabled');
            btnApprove.innerText = 'Cannot Approve (Rejected)';
        } else {
            btnApprove.disabled = false;
            btnApprove.classList.remove('disabled');
            btnApprove.innerText = 'Approve';
        }

        var myModal = new bootstrap.Modal(document.getElementById('modalApproveDH'));
        myModal.show();
    }

    // Fungsi Approval PJO
    function openApprovalPJOModal(id, status) {
        document.getElementById('doc_id_pjo').value = id;
        let btnApprovePjo = document.getElementById('btn-approve-pjo');

        if (status === 'Rejected') {
            btnApprovePjo.disabled = true;
            btnApprovePjo.classList.add('disabled');
            btnApprovePjo.innerText = 'Cannot Approve (Rejected)';
        } else {
            btnApprovePjo.disabled = false;
            btnApprovePjo.classList.remove('disabled');
            btnApprovePjo.innerText = 'Approve & Publish';
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