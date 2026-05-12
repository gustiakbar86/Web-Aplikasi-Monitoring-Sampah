<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Dashboard') - PWaste Pelindo Subregional Kalimantan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Sweet Alert -->

    <style>
        :root {
            --primary-blue: #1E3F8C;
            --primary-color: #1E3F8C;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            min-width: 100vw;
            overflow-x: hidden;
        }

        main {
            flex: 1;
            width: 100%;
        }

        /* Header Styling */
        .app-header {
            background-color: var(--primary-blue) !important;
            color: var(--white) !important;
            width: 100%;
            min-width: 100vw;
            /* reduced vertical padding to make header shorter */
            padding: 1.4rem 0;
            margin: 0;
            overflow-x: hidden;
        }

        .app-header .container-fluid {
            /* slightly smaller horizontal padding to tighten header content */
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .app-title {
            /* smaller title to reduce header height */
            font-size: 1.25rem;
            height: 40px;
            font-weight: 600;
            margin: 0;
            color: var(--white) !important;
            padding-bottom: 0.125rem;
        }

        .app-subtitle {
            font-size: 0.7rem;
            margin: 0;
            color: var(--white);
            opacity: 0.95;
        }

        .header-right {
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logout-btn {
            background-color: var(--white);
            color: var(--primary-blue);
            border: none;
            border-radius: 4px;
            /* slightly smaller button */
            padding: 0.28rem 0.9rem;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.15s ease;
        }

        .logout-btn:hover {
            background-color: #f8f9fa;
            color: var(--primary-blue);
            text-decoration: none;
        }

        /* Navbar Styling */
        .time-display {
            background-color: var(--white);
            color: var(--primary-blue);
            /* smaller time pill */
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .app-navbar {
            background-color: var(--white) !important;
            width: 100%;
            min-width: 100vw;
            margin: 0;
            /* reduced navbar height */
            padding: 1rem 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            position: relative;
            z-index: 1050;
        }

        .app-navbar .container-fluid {
            padding-left: 2rem;
            padding-right: 2rem;
        }

        .nav-pills {
            flex-wrap: nowrap;
            justify-content: center;
            padding-bottom: 5px;
            position: static;
        }

        .nav-item.dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1060;
            display: none;
            min-width: 200px;
            background-color: var(--white);
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dropdown-menu.show {
            display: block;
        }

        .nav-item.dropdown {
            position: relative;
        }

        .app-navbar {
            position: relative;
            z-index: 1030;
        }

        .nav-pills .nav-link {
            color: #333 !important;
            border-radius: 6px;
            margin: 0 0.25rem;
            /* Standardized nav links */
            padding: 8px 16px !important;
            display: flex;
            align-items: center;
            font-size: 14px !important;
            text-decoration: none;
            transition: all 0.15s ease;
            height: auto !important;
            line-height: 1.5 !important;
            white-space: nowrap;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-blue) !important;
            color: var(--white) !important;
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f0f0f0;
        }

        .nav-pills .nav-link i {
            margin-right: 0.5rem;
        }

        .nav-pills .nav-link:not(.active):hover {
            background-color: rgba(30, 63, 140, 0.1);
        }

        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            clear: both;
            text-align: inherit;
            border: 0;
        }

        .dropdown-item:hover {
            background-color: rgba(30, 63, 140, 0.1);
            color: var(--primary-blue);
        }

        /* Time Display */
        #timeDisplay {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Content Area */
        .content-area {
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            width: 89%;
            margin: 0 auto;
            padding: 1.5rem;
            z-index: 1;
        }

        /* Dashboard Area - wider layout for dashboard pages */
        .dashboard-area {
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            width: 100%;
            margin: 0;
            padding: 1.5rem;
            z-index: 1;
        }

        .app-container {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 1;
            padding: 1.5rem 0;
            margin-right: auto;
        }

        /* Footer Styling */

        .app-navbar .dropdown-item:hover {
            background-color: rgba(30, 63, 140, 0.1);
        }

        .content-area {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .dashboard-area {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            margin: 0;
        }

        .app-footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 10px 0;
            margin-top: 20px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card .icon {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .stats-card .title {
            font-size: 0.9rem;
            color: #666;
        }

        .stats-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
        }

        .table-dashboard {
            font-size: 14px;
        }

        .table-dashboard th {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .badge-lb3 {
            background-color: #dc3545;
            color: white;
        }

        .badge-sampah {
            background-color: #28a745;
            color: white;
        }

        /* Footer Styling */
        .app-footer {
            background-color: var(--primary-blue) !important;
            color: var(--white) !important;
            padding: 1rem;
            text-align: center;
            font-size: 0.9rem;
            width: 100vw;
            margin-top: auto;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }

        /* ===== RESPONSIVE MOBILE (HANYA AKTIF DI LAYAR KECIL) ===== */
        @media (max-width: 768px) {
            .app-header {
                padding: 0.8rem 1rem !important;
            }
            .app-header .row {
                flex-direction: column;
                gap: 0.8rem;
            }
            .app-header .col-6 {
                width: 100%;
                padding-left: 0.5rem !important;
            }
            .app-header h4 {
                font-size: 16px !important;
            }
            .app-header small {
                font-size: 11px !important;
            }
            .app-header img {
                width: 55px !important;
            }
            .app-navbar {
                padding: 0.5rem !important;
            }
            .app-navbar .d-flex {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.5rem !important;
            }
            .app-navbar .btn {
                width: 100%;
                justify-content: center;
                padding: 8px 16px !important;
            }
            .content-area {
                padding: 1rem !important;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .data-table {
                min-width: 800px;
            }
            .data-table th, .data-table td {
                font-size: 12px;
                padding: 0.5rem !important;
            }
        }
    </style>

    @stack('styles')

</head>
<body>
    @include('admin.partials.navbar') <div class="app-container"> @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content') </div>

    @include('admin.partials.footer')

    <!-- ✅ SweetAlert harus disini sebelum stack scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    /**
     * ===== CLEANUP SCRIPT =====
     * Mengatasi masalah backdrop nyangkut & modal tidak tertutup
     * yang menyebabkan layar hitam dan tidak bisa diklik
     */
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Hapus modal backdrop yang tersisa
        function cleanupBackdrop() {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.querySelectorAll('.offcanvas-backdrop').forEach(el => el.remove());
        }
        
        // 2. Hapus modal-open class dari body
        function cleanupBodyClass() {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
        
        // 3. Tutup semua dropdown
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                const dropdown = new bootstrap.Dropdown(toggle);
                dropdown.hide();
            });
        }
        
        // 4. Execute cleanup
        cleanupBackdrop();
        cleanupBodyClass();
        closeAllDropdowns();
        
        // 5. Monitor & cleanup setiap kali navigasi ke halaman berbeda
        // Hanya tutup dropdown jika link itu bukan bagian dari dropdown items
        document.addEventListener('click', function(e) {
            const isDropdownItem = e.target.closest('.dropdown-menu');
            const isDropdownToggle = e.target.closest('.dropdown-toggle');
            
            // Jika klik di luar dropdown, tutup semua dropdown
            if (!isDropdownItem && !isDropdownToggle) {
                // Jika klik pada link navigasi (bukan dropdown), tutup dropdown
                if (e.target.tagName === 'A' && e.target.href && !e.target.closest('.dropdown-menu')) {
                    setTimeout(() => {
                        cleanupBackdrop();
                        cleanupBodyClass();
                        closeAllDropdowns();
                    }, 100);
                }
            }
        });
        
        // 6. Cleanup sebelum SweetAlert ditampilkan
        const originalSwalFire = Swal.fire;
        Swal.fire = function(...args) {
            cleanupBackdrop();
            cleanupBodyClass();
            return originalSwalFire.apply(this, args).then(result => {
                setTimeout(() => {
                    cleanupBackdrop();
                    cleanupBodyClass();
                }, 100);
                return result;
            });
        };
        
        // 7. Cleanup saat window unload (navigasi ke halaman lain)
        window.addEventListener('beforeunload', () => {
            cleanupBackdrop();
            cleanupBodyClass();
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
