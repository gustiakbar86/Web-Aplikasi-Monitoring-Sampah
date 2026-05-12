<div class="app-header">
    <div class="container-fluid h-100">
        <div class="d-flex justify-content-between align-items-center px-4 h-100">
            <div>
                @php
                    $title = 'Dashboard Admin';
                    if(request()->routeIs('admin.dashboard')) {
                        $title = 'Dashboard Admin';
                    } elseif(request()->routeIs('admin.kelola-petugas*')) {
                        $title = 'Kelola Data Petugas';
                    } elseif(request()->routeIs('admin.dokumen.*')) {
                        $title = 'Kelola Dokumen';
                    } elseif(request()->routeIs('admin.data.sampah-terkelola')) {
                        $title = 'Sampah Terkelola';
                    } elseif(request()->routeIs('admin.data.sampah-diserahkan')) {
                        $title = 'Sampah Diserahkan(Residu)';
                    } elseif(request()->routeIs('admin.master.lokasi-asal')) {
                        $title = 'Lokasi Asal';
                    } elseif(request()->routeIs('admin.master.jenis-sampah')) {
                        $title = 'Jenis Sampah';
                    } elseif(request()->routeIs('admin.master.tujuan-sampah')) {
                        $title = 'Tujuan Sampah';
                    } elseif(request()->routeIs('admin.master.*')) {
                        $title = 'Kelola Data Master';
                    }
                @endphp

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/pwaste.png') }}" alt="Logo PWaste" style="width: 80px; height: auto; cursor: pointer; margin-left: -10px; background-color: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    </a>
                    <div>
                        <h4 class="app-title">{{ $title }}</h4>
                        <p class="app-subtitle">Aplikasi Pengelolaan Sampah Pelabuhan Banjarmasin</p>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="time-display" id="timeDisplay">--:--:-- WITA</div>

                <script>
                    (function(){
                        function pad(n){ return n.toString().padStart(2,'0'); }
                        function updateWITA(){
                            const now = new Date();
                            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                            const wita = new Date(utc + 8 * 3600000); // WITA = UTC+8
                            const h = pad(wita.getHours());
                            const m = pad(wita.getMinutes());
                            const s = pad(wita.getSeconds());
                            const el = document.getElementById('timeDisplay');
                            if(el) el.textContent = `${h}:${m}:${s} WITA`;
                        }
                        updateWITA();
                        setInterval(updateWITA, 1000);
                    })();
                </script>
                <div class="d-flex align-items-center">
                    <span class="me-2" style="font-size: 0.9rem;">Selamat Datang, Admin</span>
                    <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                </div>
                <div class="text-white" id="currentDate" style="font-size: 0.9rem;">
                    <script>
                        (function(){
                            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            const now = new Date();
                            const dayName = days[now.getDay()];
                            const date = now.getDate();
                            const monthName = months[now.getMonth()];
                            const year = now.getFullYear();
                            document.getElementById('currentDate').textContent = `${dayName}, ${date} ${monthName} ${year}`;
                        })();
                    </script>
                </div>
                <button onclick="confirmLogoutAdmin()" class="btn logout-btn ms-3" style="background-color: #ffffff; color: #1E3F8C; border: none; padding: 0.28rem 0.9rem; font-weight: 500; font-size: 0.85rem; border-radius: 4px; transition: all 0.15s ease;">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <script>
                    function confirmLogoutAdmin() {
                        Swal.fire({
                            title: 'Konfirmasi Logout',
                            text: 'Apakah Anda yakin ingin keluar dari sistem?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Logout',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('logout-form').submit();
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<div class="app-navbar">
    <div class="container-fluid px-4">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kelola-petugas') }}" class="nav-link {{ request()->routeIs('admin.kelola-petugas*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Kelola Data Petugas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dokumen.index') }}" class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}">
                    <i class="fas fa-folder me-2"></i>Kelola Dokumen
                </a>
            </li>
            <li class="nav-item dropdown">
                <button class="nav-link dropdown-toggle {{ request()->routeIs('admin.data.*') ? 'active' : '' }}" data-bs-toggle="dropdown" role="button" aria-expanded="false" style="background: none; border: none; cursor: pointer; padding: 8px 16px;">
                    <i class="fas fa-chart-bar me-2"></i>Lihat Data Sampah
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.data.sampah-terkelola') }}">Sampah Terkelola</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.data.sampah-diserahkan') }}">Sampah Diserahkan</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <button class="nav-link dropdown-toggle {{ request()->routeIs('admin.master.*') ? 'active' : '' }}" data-bs-toggle="dropdown" role="button" aria-expanded="false" style="background: none; border: none; cursor: pointer; padding: 8px 16px;">
                    <i class="fas fa-database me-2"></i>Kelola Data Master
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.master.lokasi-asal') }}">Lokasi Asal</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.master.jenis-sampah') }}">Jenis Sampah</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.master.tujuan-sampah') }}">Tujuan Sampah</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <button class="nav-link dropdown-toggle {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" data-bs-toggle="dropdown" role="button" aria-expanded="false" style="background: none; border: none; cursor: pointer; padding: 8px 16px;">
                    <i class="fas fa-chart-bar me-2"></i>Laporan
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.laporan.export-baru') }}">Export Laporan (Template)</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>