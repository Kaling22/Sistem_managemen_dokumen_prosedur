@php
    $user = Auth::user();
    $dept = strtoupper($user->departemen);
    $isAdmin = $user->role == 0;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="../assets/img/Logo PPA.png" alt="Logo" width="30">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-1">SmartPro</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>
        
        {{-- ================= DOKUMEN BARU ================= --}}
        <li class="menu-item {{ Request::is('dataDokumenBaru*', 'dataSpProduksi*', 'dataIkProduksi*', 'dataJsaProduksi*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"> 
                <i class="menu-icon tf-icons bx bx-home-circle"></i> 
                <div>Dokumen Baru</div>
            </a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopBaru.index') ? 'active' : '' }}"><a href="{{ route('dataSopBaru.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpBaru.index') ? 'active' : '' }}"><a href="{{ route('dataSpBaru.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkBaru.index') ? 'active' : '' }}"><a href="{{ route('dataIkBaru.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaBaru.index') ? 'active' : '' }}"><a href="{{ route('dataJsaBaru.index') }}" class="menu-link"><div>JSA</div></a></li>
            </ul>
        </li>


        <li class="menu-item {{ Request::is('dataDokumenBaru*', 'dataSpProduksi*', 'dataIkProduksi*', 'dataJsaProduksi*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"> 
                <i class="menu-icon tf-icons bx bx-home-circle"></i> 
                <div>Dokumen Revisi</div>
            </a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopRevisi.index') ? 'active' : '' }}"><a href="{{ route('dataSopRevisi.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpRevisi.index') ? 'active' : '' }}"><a href="{{ route('dataSpRevisi.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkRevisi.index') ? 'active' : '' }}"><a href="{{ route('dataIkRevisi.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class=""><a href="" class="menu-link"><div>JSA</div></a></li>
            </ul>
        </li>

        <li class="menu-item {{ Route::is('dataReportDoc.index') ? 'active' : '' }}">
            <a href="{{ route('dataReportDoc.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Report Dokumen</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>

        {{-- ================= PRODUKSI ================= --}}
        <li class="menu-item {{ Request::is('dataSopProduksi*', 'dataSpProduksi*', 'dataIkProduksi*', 'dataJsaProduksi*', 'dataPxProduksi*', 'dataFkProduksi*', 'dataLinkProduksi*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>Produksi</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataSopProduksi.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataSpProduksi.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataIkProduksi.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataJsaProduksi.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataPxProduksi.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataFkProduksi.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
                <li class="{{ Route::is('dataLinkProduksi.index') ? 'active' : '' }}"><a href="{{ route('dataLinkProduksi.index') }}" class="menu-link"><div>LINK FORM</div></a></li>
            </ul>
        </li>

        {{-- ================= SHE ================= --}}
        <li class="menu-item {{ Request::is('dataSopShe*', 'dataSpShe*', 'dataIkShe*', 'dataJsaShe*', 'dataPxShe*', 'dataFkShe*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>SHE</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopShe.index') ? 'active' : '' }}"><a href="{{ route('dataSopShe.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpShe.index') ? 'active' : '' }}"><a href="{{ route('dataSpShe.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkShe.index') ? 'active' : '' }}"><a href="{{ route('dataIkShe.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaShe.index') ? 'active' : '' }}"><a href="{{ route('dataJsaShe.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxShe.index') ? 'active' : '' }}"><a href="{{ route('dataPxShe.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkShe.index') ? 'active' : '' }}"><a href="{{ route('dataFkShe.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
            </ul>
        </li>

        {{-- ================= PLANT ================= --}}
        <li class="menu-item {{ Request::is('dataSopPlant*', 'dataSpPlant*', 'dataIkPlant*', 'dataJsaPlant*', 'dataPxPlant*', 'dataFkPlant*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>PLANT</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopPlant.index') ? 'active' : '' }}"><a href="{{ route('dataSopPlant.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpPlant.index') ? 'active' : '' }}"><a href="{{ route('dataSpPlant.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkPlant.index') ? 'active' : '' }}"><a href="{{ route('dataIkPlant.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaPlant.index') ? 'active' : '' }}"><a href="{{ route('dataJsaPlant.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxPlant.index') ? 'active' : '' }}"><a href="{{ route('dataPxPlant.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkPlant.index') ? 'active' : '' }}"><a href="{{ route('dataFkPlant.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
            </ul>
        </li>

        {{-- ================= FALOG ================= --}}
        <li class="menu-item {{ Request::is('dataSopFalog*', 'dataSpFalog*', 'dataIkFalog*', 'dataJsaFalog*', 'dataPxFalog*', 'dataFkFalog*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>FALOG</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopFalog.index') ? 'active' : '' }}"><a href="{{ route('dataSopFalog.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpFalog.index') ? 'active' : '' }}"><a href="{{ route('dataSpFalog.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkFalog.index') ? 'active' : '' }}"><a href="{{ route('dataIkFalog.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaFalog.index') ? 'active' : '' }}"><a href="{{ route('dataJsaFalog.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxFalog.index') ? 'active' : '' }}"><a href="{{ route('dataPxFalog.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkFalog.index') ? 'active' : '' }}"><a href="{{ route('dataFkFalog.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
            </ul>
        </li>

        {{-- ================= HCGA ================= --}}
        <li class="menu-item {{ Request::is('dataSopHcga*', 'dataSpHcga*', 'dataIkHcga*', 'dataJsaHcga*', 'dataPxHcga*', 'dataFkHcga*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>HCGA</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopHcga.index') ? 'active' : '' }}"><a href="{{ route('dataSopHcga.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpHcga.index') ? 'active' : '' }}"><a href="{{ route('dataSpHcga.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkHcga.index') ? 'active' : '' }}"><a href="{{ route('dataIkHcga.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaHcga.index') ? 'active' : '' }}"><a href="{{ route('dataJsaHcga.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxHcga.index') ? 'active' : '' }}"><a href="{{ route('dataPxHcga.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkHcga.index') ? 'active' : '' }}"><a href="{{ route('dataFkHcga.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
            </ul>
        </li>

        {{-- ================= ICTMD ================= --}}
        <li class="menu-item {{ Request::is('dataSopIctmd*', 'dataSpIctmd*', 'dataIkIctmd*', 'dataJsaIctmd*', 'dataPxIctmd*', 'dataFkIctmd*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>ICTMD</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopIctmd.index') ? 'active' : '' }}"><a href="{{ route('dataSopIctmd.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpIctmd.index') ? 'active' : '' }}"><a href="{{ route('dataSpIctmd.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkIctmd.index') ? 'active' : '' }}"><a href="{{ route('dataIkIctmd.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaIctmd.index') ? 'active' : '' }}"><a href="{{ route('dataJsaIctmd.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxIctmd.index') ? 'active' : '' }}"><a href="{{ route('dataPxIctmd.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkIctmd.index') ? 'active' : '' }}"><a href="{{ route('dataFkIctmd.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
            </ul>
        </li>

        {{-- ================= ENGINEERING ================= --}}
        <li class="menu-item {{ Request::is('dataSopEngineering*', 'dataSpEngineering*', 'dataIkEngineering*', 'dataJsaEngineering*', 'dataPxEngineering*', 'dataFkEngineering*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>ENGINEERING</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataSopEngineering.index') ? 'active' : '' }}"><a href="{{ route('dataSopEngineering.index') }}" class="menu-link"><div>SOP</div></a></li>
                <li class="{{ Route::is('dataSpEngineering.index') ? 'active' : '' }}"><a href="{{ route('dataSpEngineering.index') }}" class="menu-link"><div>SP</div></a></li>
                <li class="{{ Route::is('dataIkEngineering.index') ? 'active' : '' }}"><a href="{{ route('dataIkEngineering.index') }}" class="menu-link"><div>IK</div></a></li>
                <li class="{{ Route::is('dataJsaEngineering.index') ? 'active' : '' }}"><a href="{{ route('dataJsaEngineering.index') }}" class="menu-link"><div>JSA</div></a></li>
                <li class="{{ Route::is('dataPxEngineering.index') ? 'active' : '' }}"><a href="{{ route('dataPxEngineering.index') }}" class="menu-link"><div>PROSEDUR EXTERNAL</div></a></li>
                <li class="{{ Route::is('dataFkEngineering.index') ? 'active' : '' }}"><a href="{{ route('dataFkEngineering.index') }}" class="menu-link"><div>FORMULIR KERJA</div></a></li>
            </ul>
        </li>

        {{-- ================= INFORMASI ================= --}}
        <li class="menu-item {{ Request::is('dataKebijakan*', 'dataMemo*', 'dataMemoInternal*', 'dataInstruksiKtt*', 'dataPoster*', 'dataMsds*', 'dataBap*', 'dataSertifikatSIO*', 'dataMocMprp*', 'dataIbpr*') ? 'active open' : '' }}">
            <a class="menu-link menu-toggle"><div>INFORMASI</div></a>
            <ul class="menu-sub">
                <li class="{{ Route::is('dataKebijakan.index') ? 'active' : '' }}"><a href="{{ route('dataKebijakan.index') }}" class="menu-link"><div>KEBIJAKAN</div></a></li>
                <li class="{{ Route::is('dataMemo.index') ? 'active' : '' }}"><a href="{{ route('dataMemo.index') }}" class="menu-link"><div>MEMO External</div></a></li>
                <li class="{{ Route::is('dataMemoInternal.index') ? 'active' : '' }}"><a href="{{ route('dataMemoInternal.index') }}" class="menu-link"><div>MEMO Internal</div></a></li>
                <li class="{{ Route::is('dataInstruksiKtt.index') ? 'active' : '' }}"><a href="{{ route('dataInstruksiKtt.index') }}" class="menu-link"><div>INSTRUKSI KTT</div></a></li>
                <li class="{{ Route::is('dataPoster.index') ? 'active' : '' }}"><a href="{{ route('dataPoster.index') }}" class="menu-link"><div>POSTER</div></a></li>
                <li class="{{ Route::is('dataMsds.index') ? 'active' : '' }}"><a href="{{ route('dataMsds.index') }}" class="menu-link"><div>MSDS</div></a></li>
                <li class="{{ Route::is('dataBap.index') ? 'active' : '' }}"><a href="{{ route('dataBap.index') }}" class="menu-link"><div>BAP</div></a></li>
                <li class="{{ Route::is('dataSertifikatSIO.index') ? 'active' : '' }}"><a href="{{ route('dataSertifikatSIO.index') }}" class="menu-link"><div>SERTIFIKAT & SIO</div></a></li>
                <li class="{{ Route::is('dataMocMprp.index') ? 'active' : '' }}"><a href="{{ route('dataMocMprp.index') }}" class="menu-link"><div>MOC & MPRP</div></a></li>
                <li class="{{ Route::is('dataIbpr.index') ? 'active' : '' }}"><a href="{{ route('dataIbpr.index') }}" class="menu-link"><div>IBPR</div></a></li>
            </ul>
        </li>

        {{-- ================= ADMIN ONLY ================= --}}
        @if($isAdmin || Auth::user()->role==1 )
        <li class="menu-item {{ Route::is('auth.index') ? 'active' : '' }}">
            <a href="{{ route('auth.index') }}" class="menu-link">
                <div>DATA AKUN</div>
            </a>
        </li>
        @endif

    </ul>
</aside>