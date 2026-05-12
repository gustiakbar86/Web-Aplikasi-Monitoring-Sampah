<!-- Navigation Bar -->
<nav class="app-navbar bg-white">
    <div class="container-fluid px-4">
        <div class="d-flex gap-3 py-2 justify-content-center">
            <a href="{{ route('petugas.dashboard') }}" class="btn {{ request()->is('petugas/dashboard') ? 'btn-primary' : 'btn-light' }} d-inline-flex align-items-center">
                <i class="fas fa-home"></i>
                <span class="ms-2">Dashboard</span>
            </a>
            <div class="dropdown">
                <button class="btn {{ request()->is('petugas/input-*') ? 'btn-primary' : 'btn-light' }} dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus-circle"></i>
                    <span class="ms-2">Input Data Sampah</span>
                </button>
                <ul class="dropdown-menu mt-1">
                    <li>
                        <a class="dropdown-item {{ request()->is('petugas/input-sampah-terkelola') ? 'active' : '' }}" href="{{ route('petugas.input-sampah-terkelola') }}">
                            Sampah Terkelola
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('petugas/input-sampah-diserahkan') ? 'active' : '' }}" href="{{ route('petugas.input-sampah-diserahkan') }}">
                            Sampah Diserahkan(Residu)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn {{ request()->is('petugas/sampah-*') ? 'btn-primary' : 'btn-light' }} dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-history"></i>
                    <span class="ms-2">Lihat Riwayat Inputan</span>
                </button>
                <ul class="dropdown-menu mt-1">
                    <li>
                        <a class="dropdown-item {{ request()->is('petugas/sampah-terkelola') ? 'active' : '' }}" href="{{ route('petugas.sampah-terkelola') }}">
                            Sampah Terkelola
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('petugas/sampah-diserahkan') ? 'active' : '' }}" href="{{ route('petugas.sampah-diserahkan') }}">
                            Sampah Diserahkan(Residu)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>