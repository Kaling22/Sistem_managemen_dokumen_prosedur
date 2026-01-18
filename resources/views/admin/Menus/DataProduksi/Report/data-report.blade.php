@extends ('layouts.main')
@section('container')
<!-- Content -->

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">DATA REPORT USER DEPARTEMEN PRODUKSI</h5>
    @if(Auth::user()->role==0)
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
          <th>No Report</th>
          <th>Jenis Dokumen</th>
          <th>Judul</th>
          <th>Nomer Dokumen</th>
          <th>Pelapor</th>
          <th>NRP Pelapor</th>
          <th>Isi</th>
          <th>Feedback</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <?php $index = 1; ?>
      @foreach ($report as $item)
        <tr>
            <td> <strong>{{$index++}}</strong></td>
            <td>{{$item->report_number}}</td>
            <td>SOP</td>
            <td>{{$item->doc_name}}</td>
            <td>{{$item->doc_number}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->nrp}}</td>
            <td>{{$item->isi_report}}</td>
            <td>{{$item->feedback}}</td>
            <td>
                <x-status-badge :status="$item->status" />
            </td>
            <td>
            @if(in_array(Auth::user()->role, [0,1]))
            <a href="{{ route('dataReportProduksi.edit', $item->id) }}"class="btn btn-sm btn-secondary">Edit</a>
            <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                action="{{ route('dataReportProduksi.destroy', $item->id) }}" method="POST">
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