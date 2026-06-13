@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">Tabel Data SP FALOG</h5>
    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
    <a href="{{route('dataSpFalog.inactive')}}" type="button" class="btn btn-primary" >
            Dokumen Tidak Aktif
        </a>
    <a href="{{route('export.induk.sp')}}" type="button" class="btn btn-primary" >
            Daftar Induk Dokumen
        </a>
    @elseif(Auth::user()->role==1)
    <a href="{{route('dataSpFalog.inactive')}}" type="button" class="btn btn-primary" >
            Dokumen Tidak Aktif
        </a>
    <a href="{{route('export.induk.sp')}}" type="button" class="btn btn-primary" >
            Daftar Induk Dokumen
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
          <th>No SP</th>
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
      @foreach ($sp as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->no_dokumen}}</td>
            <td>{{$item->judul}}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/sp/'.$item->file) }}" target="_blank">
                        File Dokumen
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>{{$item->edisi}}</td>
            <td>{{$item->revisi}}</td>
            <td>{{ $item->efektif_date ? date('d/m/Y', strtotime($item->efektif_date)) : '-' }}</td>
            <td>
            @if(Auth::user()->role==0||Auth::user()->role==1)
            <a href="javascript:void(0)" 
              onclick="handleRevision('{{ $item->no_dokumen }}', '{{ route('dataSpFalog.edit', $item->id) }}')" 
              class="btn btn-sm btn-warning">
              Revisi
            </a>
            <!-- <a href="{{ route('dataSpFalog.edit', $item->id) }}"class="btn btn-sm btn-secondary">Revisi</a> -->
            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                action="{{ route('dataSpFalog.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
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

<div class="modal fade" id="modalWarningRevisi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-top border-danger border-3">
            <div class="modal-header">
                <h5 class="modal-title text-danger">⚠️ Revisi Sedang Berjalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="msg-warning-revisi"></p>
                <div class="alert alert-secondary text-start mt-3">
                    <small>
                        <strong>Status:</strong> <span id="status-revisi"></span><br>
                        <strong>Diajukan Oleh:</strong> <span id="user-revisi"></span>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<script>
function handleRevision(noDokumen, editUrl) {
    // Encode no_dokumen jika mengandung karakter '/'
    const safeNoDok = encodeURIComponent(noDokumen.replace(/\//g, '-'));

    // Panggil API Pengecekan
    fetch(`/check-revisi/${safeNoDok}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'exists') {
                // Jika sedang ada proses, tampilkan Modal
                document.getElementById('msg-warning-revisi').innerText = data.message;
                document.getElementById('status-revisi').innerText = data.detail.status_saat_ini;
                document.getElementById('user-revisi').innerText = data.detail.oleh;
                
                var myModal = new bootstrap.Modal(document.getElementById('modalWarningRevisi'));
                myModal.show();
            } else {
                // Jika aman, lanjut ke halaman revisi
                window.location.href = editUrl;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: jika error tetap izinkan masuk (opsional)
            window.location.href = editUrl;
        });
}
</script>

@endsection