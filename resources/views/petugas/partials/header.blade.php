<!-- Header Section -->
<header class="app-header bg-primary">
    <div class="container-fluid px-4 py-2">
        <div class="row">
            <div class="col-6">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('petugas.dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/pwaste.png') }}" alt="Logo PWaste" style="width: 80px; height: auto; cursor: pointer; margin-left: -10px; background-color: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    </a>
                    <div>
                        <h4 class="page-title mb-0 text-white">@yield('title', 'Dashboard')</h4>
                        <small class="text-white-50">Aplikasi Pengelolaan Sampah Pelabuhan Banjarmasin</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end align-items-center h-100">
                    <div class="time-display me-3" id="timeDisplay" style="background-color: #ffffff; color: #1E3F8C; font-size: 14px; font-weight: 600; padding: 6px 12px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        --:--:-- WITA
                    </div>

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

                    <div class="d-flex align-items-center text-white me-3">
                        <span>Selamat Datang, Petugas</span>
                        <i class="fas fa-circle text-success ms-2" style="font-size: 8px;"></i>
                    </div>
                    <div class="text-white me-3" id="currentDate" style="font-size: 14px;">
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
                    <button onclick="confirmLogoutPetugas()" class="btn" style="background-color: #ffffff; color: #1E3F8C; border: none; padding: 0.35rem 1rem; font-weight: 500; font-size: 0.85rem; border-radius: 4px; transition: all 0.15s ease;">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <script>
                        function confirmLogoutPetugas() {
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
</header>