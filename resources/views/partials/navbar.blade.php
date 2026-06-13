<div class="layout-page">
<nav
class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
id="layout-navbar"
>
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="bx bx-menu bx-sm"></i>
    </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    
    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">

    <!-- Notifikasi Approval -->
    <li class="nav-item dropdown me-3 me-xl-2">
        <a class="nav-link dropdown-toggle hide-arrow position-relative" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-sm bx-bell"></i>
            @if(($notifTotal ?? 0) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: .65rem;">
                    {{ $notifTotal > 99 ? '99+' : $notifTotal }}
                    <span class="visually-hidden">dokumen menunggu</span>
                </span>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end py-0" style="min-width: 320px;">
            <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                    <h6 class="mb-0 me-auto">Notifikasi</h6>
                    @if(($notifTotal ?? 0) > 0)
                        <span class="badge bg-label-primary">{{ $notifTotal }} Baru</span>
                    @endif
                </div>
            </li>
            @forelse(($notifItems ?? []) as $n)
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ $n['url'] }}">
                        <i class="bx {{ $n['icon'] }} bx-sm me-2 text-warning"></i>
                        <span class="flex-grow-1">{{ $n['label'] }}</span>
                        <span class="badge bg-danger rounded-pill ms-2">{{ $n['count'] }}</span>
                    </a>
                </li>
            @empty
                <li>
                    <span class="dropdown-item text-muted py-3">
                        <i class="bx bx-check-circle me-1"></i> Tidak ada dokumen menunggu persetujuan.
                    </span>
                </li>
            @endforelse
        </ul>
    </li>
    <!--/ Notifikasi -->

    <li class="nav-item me-2 me-xl-0">
        <a class="nav-link style-switcher-toggle" href="javascript:void(0);" id="darkModeToggle">
            <i class="bx bx-sm bx-moon" id="darkModeIcon"></i>
        </a>
    </li>
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="../assets/img/avatars/user.png" alt class="w-px-40 h-auto rounded-circle" />
        </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="#">
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/user.png" alt class="w-px-40 h-auto rounded-circle" />
                </div>
                </div>
                <div class="flex-grow-1">
                    
                <span class="fw-semibold d-block">{{ Auth::user()->nama }}</span>
                <small class="text-muted">
                    Role <span class="fw-bold">{{ Auth::user()->role_name }}</span>
                </small>
                </div>
            </div>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <form action="/logout" method="POST"> 
                @csrf
                <button class="dropdown-item" type="submit">
                    <i class="bx bx-power-off me-2"></i>
                    <span class="align-middle">Log Out</span>
                    </a>
                </button>
            </form>
            
        </li>
        </ul>
    </li>
    <!--/ User -->
    </ul>
</div>
</nav>
<div class="container-xxl flex-grow-1 container-p-y">

<script>
    (function () {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        const htmlElement = document.documentElement; // Tag <html>

        // Sinkronkan ikon dengan tema yang sedang aktif (kelas sudah dipasang lebih awal di <head>).
        function syncIcon() {
            const isDark = htmlElement.classList.contains('dark-mode');
            darkModeIcon.classList.toggle('bx-sun', isDark);
            darkModeIcon.classList.toggle('bx-moon', !isDark);
        }
        syncIcon();

        darkModeToggle.addEventListener('click', function () {
            const isDark = htmlElement.classList.toggle('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            syncIcon();
        });
    })();
</script>