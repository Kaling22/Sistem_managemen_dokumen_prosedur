@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">Tabel Data IK FALOG</h5>
    @if(Auth::user()->role==0)
    <a href="{{route('dataIkFalog.inactive')}}" type="button" class="btn btn-primary" >
            Dokumen Tidak Aktif
        </a>  
    <a href="{{route('export.induk.ik')}}" type="button" class="btn btn-primary" >
      Daftar Induk Dokumen
    </a>
    @elseif(Auth::user()->role==1)
    <a href="{{route('dataIkFalog.inactive')}}" type="button" class="btn btn-primary" >
            Dokumen Tidak Aktif
        </a>
    <a href="{{route('export.induk.ik')}}" type="button" class="btn btn-primary" >
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
          <th>No IK</th>
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
      @foreach ($ik as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->no_dokumen}}</td>
            <td>{{$item->judul}}</td>
            <td>
                @if($item->file)
                    <a href="{{ asset('storage/ik/'.$item->file) }}" target="_blank">
                        File Dokumen
                    </a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </td>
            <td>{{$item->edisi}}</td>
            <td>{{$item->revisi}}</td>
            <td>{{$item->efektif_date}}</td>
            <td>
            @if(Auth::user()->role==0)
            <a href="javascript:void(0)" 
              onclick="handleRevision('{{ $item->no_dokumen }}', '{{ route('dataIkFalog.edit', $item->id) }}')" 
              class="btn btn-sm btn-warning">
              Revisi
            </a>
            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                action="{{ route('dataIkFalog.destroy', $item->id) }}" method="POST">
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