@extends ('layouts.main')
@section('container')
  <!-- Content -->
    <div class="row">
      <div class="col-lg-12 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">Selamat Datang {{Auth::user()->nama}}!</h5>
                <p class="mb-4">
                @if(Auth::user()->role==0)
                  Anda telah melakukan login sebagai <span class="fw-bold">Admin</span>.
                @elseif(Auth::user()->role==1)
                  Anda telah melakukan login sebagai <span class="fw-bold">Anggota</span>.
                @else
                  Jenis Akun Tidak Memiliki Akses.
                @endif
                </p>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img
                  src="../assets/img/illustrations/man-with-laptop-light.png"
                  height="140"
                  alt="View Badge User"
                  data-app-dark-img="illustrations/man-with-laptop-dark.png"
                  data-app-light-img="illustrations/man-with-laptop-light.png"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>

  <!-- / Content -->
  @endsection