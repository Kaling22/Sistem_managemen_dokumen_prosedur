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
    <!-- Place this tag where you want the button to render. -->
    
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
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const htmlElement = document.documentElement; // Mengambil tag <html>

    // 1. Cek local storage saat halaman dimuat
    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

    if (currentTheme) {
        htmlElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') {
            darkModeIcon.classList.replace('bx-moon', 'bx-sun');
        }
    }

    // 2. Fungsi saat tombol diklik
    darkModeToggle.addEventListener('click', function() {
        let theme = htmlElement.getAttribute('data-theme');
        
        if (theme === 'dark') {
            // Ubah ke Light Mode
            htmlElement.setAttribute('data-theme', 'light');
            darkModeIcon.classList.replace('bx-sun', 'bx-moon');
            localStorage.setItem('theme', 'light');
        } else {
            // Ubah ke Dark Mode
            htmlElement.setAttribute('data-theme', 'dark');
            darkModeIcon.classList.replace('bx-moon', 'bx-sun');
            localStorage.setItem('theme', 'dark');
        }
    });
</script>