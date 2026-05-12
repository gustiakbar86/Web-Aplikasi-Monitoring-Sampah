<!-- Super Admin Navigation Bar -->
<nav class="app-navbar bg-white w-100">
    <div class="w-100">
        <div class="d-flex gap-3 py-2 justify-content-center">
            <a href="{{ route('superadmin.dashboard') }}" class="btn d-inline-flex align-items-center {{ request()->is('superadmin/dashboard') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="ms-2">Dashboard</span>
            </a>
            <div class="dropdown">
                <button class="btn dropdown-toggle d-inline-flex align-items-center {{ request()->is('superadmin/master*') ? 'btn-primary text-white' : 'btn-outline-primary' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-database"></i>
                    <span class="ms-2">Lihat Data Master</span>
                </button>
                <ul class="dropdown-menu mt-1">
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/users') ? 'active' : '' }}" href="{{ route('superadmin.master.users') }}">
                            <i class="fas fa-users me-2"></i>Data User/Petugas
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/instansi') ? 'active' : '' }}" href="{{ route('superadmin.master.instansi') }}">
                            <i class="fas fa-building me-2"></i>Data Instansi
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/sampah-terkelola') ? 'active' : '' }}" href="{{ route('superadmin.master.sampah-terkelola') }}">
                            <i class="fas fa-recycle me-2"></i>Data Sampah Terkelola
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/sampah-diserahkan') ? 'active' : '' }}" href="{{ route('superadmin.master.sampah-diserahkan') }}">
                            <i class="fas fa-truck me-2"></i>Data Sampah Diserahkan(Residu)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/lokasi-asal') ? 'active' : '' }}" href="{{ route('superadmin.master.lokasi-asal') }}">
                            <i class="fas fa-map-marker-alt me-2"></i>Lokasi Asal Sampah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/jenis-sampah') ? 'active' : '' }}" href="{{ route('superadmin.master.jenis-sampah') }}">
                            <i class="fas fa-trash-alt me-2"></i>Jenis Sampah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/tujuan-sampah') ? 'active' : '' }}" href="{{ route('superadmin.master.tujuan-sampah') }}">
                            <i class="fas fa-arrow-right me-2"></i>Tujuan Sampah
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/dokumen') ? 'active' : '' }}" href="{{ route('superadmin.master.dokumen') }}">
                            <i class="fas fa-file-contract me-2"></i>Kelola Dokumen
                        </a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle d-inline-flex align-items-center {{ request()->is('superadmin/laporan*') ? 'btn-primary text-white' : 'btn-outline-primary' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-chart-bar"></i>
                    <span class="ms-2">Kelola Laporan</span>
                </button>
                <ul class="dropdown-menu mt-1">
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/export-baru') ? 'active' : '' }}" href="{{ route('superadmin.laporan.export-baru') }}">
                            <i class="fas fa-file-excel me-2"></i>Export Laporan (Template)
                        </a>
                    </li>
                    <!-- <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/harian') ? 'active' : '' }}" href="{{ route('superadmin.laporan.harian') }}">
                            <i class="fas fa-calendar-day me-2"></i>Laporan Harian
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/mingguan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.mingguan') }}">
                            <i class="fas fa-calendar-week me-2"></i>Laporan Mingguan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/bulanan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.bulanan') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Laporan Bulanan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/tahunan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.tahunan') }}">
                            <i class="fas fa-calendar me-2"></i>Laporan Tahunan
                        </a>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    .app-navbar .btn-primary,
    .app-navbar .btn-primary:hover,
    .app-navbar .btn-primary:focus,
    .app-navbar .btn-primary:active {
        background-color: #1E3F8C !important;
        border-color: #1E3F8C !important;
        color: #fff !important;
    }

    .app-navbar .btn-outline-primary {
        color: #1E3F8C !important;
        border-color: #1E3F8C !important;
    }

    .app-navbar .btn-outline-primary:hover {
        background-color: #1E3F8C !important;
        color: #fff !important;
        border-color: #1E3F8C !important;
    }

    .dropdown-item.active {
        background-color: #1e3f8c;
        color: #fff;
    }
    
    .dropdown-item.active:hover {
        background-color: #162f6b;
        color: #fff;
    }
</style>