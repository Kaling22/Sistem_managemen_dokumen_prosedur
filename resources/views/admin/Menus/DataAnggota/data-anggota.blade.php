@extends ('layouts.main')

@section('container')
<div class="card">

  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-header">Tabel Data Anggota</h5>

    @if(Auth::user()->role === \App\Models\User::ROLE_ADMIN || Auth::user()->role === \App\Models\User::ROLE_DOCO)
      <a href="{{ route('auth.create') }}" class="btn btn-primary">
        Tambah Anggota Baru
      </a>
    @endif
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>NRP</th>
          <th>Nama</th>
          <th>Kontak</th>
          <th>Role</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>

      <tbody class="table-border-bottom-0">
        @foreach ($anggota as $index => $item)
        <tr>
          <td><strong>{{ $index + 1 }}</strong></td>
          <td>{{ $item->nrp }}</td>
          <td>{{ $item->nama }}</td>
          <td>{{ $item->kontak }}</td>
          <td>{{ $item->role_name }}</td>

          <td class="text-center">
            @if(Auth::user()->role === \App\Models\User::ROLE_ADMIN || Auth::user()->role === \App\Models\User::ROLE_DOCO)

              <a href="{{ route('auth.edit', $item->id) }}"
                 class="btn btn-sm btn-secondary">
                Edit
              </a>

              <form action="{{ route('auth.destroy', $item->id) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Apakah Anda yakin?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  Hapus
                </button>
              </form>

            @else
              <span class="badge bg-secondary">No Access</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
@endsection
