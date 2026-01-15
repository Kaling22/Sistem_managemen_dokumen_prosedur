<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
    <a href="" class="app-brand-link">
        <span class="app-brand-logo demo">
            <img src="../assets/img/Logo PPA.png" alt="Logo" width="30">
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-1">ProWork DOCO</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item active">
        <a href="/dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
        </a>
    </li>
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Pages</span>
    </li>
    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>Produksi</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopProduksi.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpProduksi.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkProduksi.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaProduksi.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataPxProduksi.index') }}" class="menu-link">
                <div>PROSEDUR EXTERNAL</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataFkProduksi.index') }}" class="menu-link">
                <div>FORMULIR KERJA</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataLinkProduksi.index') }}" class="menu-link">
                <div>LINK FORM</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>SHE</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopShe.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpShe.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkShe.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaShe.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataPxShe.index') }}" class="menu-link">
                <div>PROSEDUR EXTERNAL</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataFkShe.index') }}" class="menu-link">
                <div>FORMULIR KERJA</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>PLANT</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopPlant.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpPlant.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkPlant.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaPlant.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>FALOG</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopFalog.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpFalog.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkFalog.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaFalog.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
            </li>
                <li class="menu-item">
                <a href="{{ route('dataPxFalog.index') }}" class="menu-link">
                <div>PROSEDUR EXTERNAL</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataFkFalog.index') }}" class="menu-link">
                <div>FORMULIR KERJA</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>HCGA</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopHcga.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpHcga.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkHcga.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaHcga.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
            </li>
                <li class="menu-item">
                <a href="{{ route('dataPxHcga.index') }}" class="menu-link">
                <div>PROSEDUR EXTERNAL</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataFkHcga.index') }}" class="menu-link">
                <div>FORMULIR KERJA</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>COE</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopCoe.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpCoe.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkCoe.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaCoe.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
            </li>
                <li class="menu-item">
                <a href="{{ route('dataPxCoe.index') }}" class="menu-link">
                <div>PROSEDUR EXTERNAL</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataFkCoe.index') }}" class="menu-link">
                <div>FORMULIR KERJA</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>ENGINEERING</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataSopEngineering.index') }}" class="menu-link">
                <div>SOP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSpEngineering.index') }}" class="menu-link">
                <div>SP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIkEngineering.index') }}" class="menu-link">
                <div>IK</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataJsaEngineering.index') }}" class="menu-link">
                <div>JSA</div>
                </a>
            </li>
            </li>
                <li class="menu-item">
                <a href="{{ route('dataPxEngineering.index') }}" class="menu-link">
                <div>PROSEDUR EXTERNAL</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataFkEngineering.index') }}" class="menu-link">
                <div>FORMULIR KERJA</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a  class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>INFORMASI</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('dataKebijakan.index') }}" class="menu-link">
                <div>KEBIJAKAN</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataMemo.index') }}" class="menu-link">
                <div>MEMO External</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataMemoInternal.index') }}" class="menu-link">
                <div>MEMO Internal</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataInstruksiKtt.index') }}" class="menu-link">
                <div>INSTRUKSI KTT</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataPoster.index') }}" class="menu-link">
                <div>POSTER</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataMsds.index') }}" class="menu-link">
                <div>MSDS</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataBap.index') }}" class="menu-link">
                <div>BAP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataSertifikatSIO.index') }}" class="menu-link">
                <div>SERTIFIKAT & SIO</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataMocMprp.index') }}" class="menu-link">
                <div>MOC & MPRP</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('dataIbpr.index') }}" class="menu-link">
                <div>IBPR</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="{{ route('auth.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div>DATA AKUN</div>
        </a>
    </li>
</aside>
