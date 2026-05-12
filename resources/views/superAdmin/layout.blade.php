<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Super Admin Dashboard') - PWaste Pelindo Subregional Kalimantan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }

        .app-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            max-width: 100%;
        }

        html {
            width: 100%;
            overflow-x: hidden;
        }
        .app-navbar .btn,
        .app-navbar button {
            font-size: 14px !important;
            padding: 8px 16px !important;
            height: auto !important;
            line-height: 1.5 !important;
        }

        .app-navbar .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
            background-color: transparent;
            box-shadow: none;
        }

        .app-navbar .btn-outline-primary:hover {
            color: white;
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            box-shadow: none;
        }

        .app-navbar .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white !important;
        }

        .app-navbar .btn-primary:hover,
        .app-navbar .btn-primary:focus,
        .app-navbar .btn-primary:active {
            background-color: #162f6b;
            border-color: #162f6b;
            color: white !important;
        }

        .app-navbar .dropdown-item.active {
            background-color: var(--primary-blue);
        }

        main {
            flex: 1;
            width: 100%;
        }

        .app-container {
            flex: 1;
            width: 100%;
        }

        .content-wrapper {
            padding: 20px;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            flex: 1;
            overflow-x: hidden;
        }

        .content-wrapper .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .content-wrapper .col-md-6,
        .content-wrapper [class*='col-'] {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        /* Header Styling */
        .app-header {
            background-color: var(--primary-blue) !important;
            color: var(--white) !important;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 14px;
            margin-bottom: 0;
            color: #666;
        }

        /* Navbar Styling */
        .app-navbar {
            background-color: var(--white);
            padding: 0.55rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .app-navbar .nav-link {
            color: #333;
            padding: 8px 16px !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            min-width: 0;
            height: auto !important;
            margin: 0 0.25rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: inline-flex !important;
            align-items: center;
        }

        .app-navbar .nav-link:hover,
        .app-navbar .nav-link.active {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .dropdown-menu {
            background-color: var(--white);
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
            min-width: 220px;
        }

        .dropdown-item {
            padding: 8px 15px;
            color: var(--text-color);
            border-radius: 4px;
            margin-bottom: 2px;
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: rgba(30, 63, 140, 0.1);
            color: var(--primary-color);
        }

        /* Content Area Variants - untuk digunakan di masing-masing blade */
        .content-area-dashboard {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .content-area-table {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .content-area-form {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .filter-form {
            background-color: var(--primary-color);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            color: var(--white);
        }

        .chart-container {
            background-color: var(--primary-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            height: 350px;
            color: var(--white);
        }

        .stats-card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-card .title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stats-card .value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table th {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 10px;
            text-align: center;
        }
        .stats-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .stats-table tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            color: white !important;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0099cc;
            color: white !important;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
        }

        .current-date {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .user-info {
            color: var(--primary-color);
            font-size: 14px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: #F0AD4E;
            color: #333;
            font-weight: 600;
            text-align: center;
            padding: 10px;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .data-table tr:last-child {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }

        .data-table tr:nth-child(odd):not(:last-child) {
            background-color: #f9f9f9;
        }

        .pie-chart-legend {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            color: white;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            margin-right: 8px;
            border-radius: 50%;
        }
        /* Footer Styling */
        .app-footer {
            background-color: var(--primary-blue);
            color: var(--white);
            text-align: center;
            padding: 15px 0;
            margin-top: 0;
            width: 100%;
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white !important;
        }

        .btn-primary:hover {
            background-color: #15306B;
            border-color: #15306B;
            color: white !important;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white !important;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white !important;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white !important;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white !important;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white !important;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white !important;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529 !important;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529 !important;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white !important;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white !important;
        }

        .btn-logout {
            background-color: #dc3545;
            color: var(--white);
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #bb2d3b;
            color: var(--white);
        }

        /* Utility Classes */
        .current-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .user-info {
            font-size: 0.9rem;
            color: var(--white);
        }

        /* Data Table Styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: var(--white);
        }

        .data-table th {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 1rem;
            text-align: left;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Chart Styling */
        .pie-chart-legend {
            padding: 1rem;
            background-color: var(--white);
            border-radius: 4px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 0.5rem;
            border-radius: 3px;
        }

        /* Content Area Styling removed - use variants instead */

        .filter-form {
            margin-bottom: 2rem;
        }

        /* ===== RESPONSIVE MOBILE (HANYA AKTIF DI LAYAR KECIL) ===== */
        @media (max-width: 768px) {
            /* Header Mobile */
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
                padding-right: 0.5rem !important;
            }
            
            .app-header h4 {
                font-size: 16px !important;
            }
            
            .app-header small {
                font-size: 11px !important;
            }
            
            .app-header img {
                width: 55px !important;
                margin-left: 0 !important;
            }
            
            .app-header .d-flex {
                flex-wrap: wrap;
                justify-content: center !important;
                gap: 0.5rem !important;
            }
            
            .time-display {
                font-size: 12px !important;
                padding: 4px 8px !important;
            }
            
            .logout-btn {
                font-size: 12px !important;
                padding: 4px 10px !important;
            }

            /* Navbar Mobile */
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
                font-size: 14px !important;
                padding: 8px 16px !important;
            }
            
            .app-navbar .dropdown {
                width: 100%;
            }
            
            .app-navbar .dropdown-toggle {
                width: 100%;
            }

            /* Content Mobile */
            .content-area-dashboard,
            .content-area-table,
            .content-area-form {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }
            
            .filter-form {
                padding: 1rem !important;
            }
            
            .filter-form .d-flex {
                flex-direction: column !important;
                gap: 0.8rem !important;
            }
            
            .filter-form .me-3 {
                margin-right: 0 !important;
                margin-bottom: 0.5rem;
            }
            
            .filter-form label {
                display: block;
                margin-bottom: 0.3rem;
            }

            /* Table Mobile - Scroll Horizontal */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .data-table {
                min-width: 800px;
            }
            
            .data-table th,
            .data-table td {
                font-size: 12px;
                padding: 0.5rem !important;
                white-space: nowrap;
            }

            /* Chart Mobile */
            .chart-container {
                height: 250px !important;
                padding: 1rem !important;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            canvas {
                max-height: 200px !important;
            }

            /* Stats Card Mobile */
            .stats-card {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }
            
            .stats-card .title {
                font-size: 14px !important;
            }
            
            .stats-card .value {
                font-size: 20px !important;
            }

            /* Footer Mobile */
            .app-footer {
                padding: 1rem !important;
                font-size: 12px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @include('superAdmin.partials.header')
        @include('superAdmin.partials.navbar')

        <div class="content-wrapper">
            @yield('content')
        </div>

        @include('superAdmin.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
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
        
        // 5. Monitor & cleanup setiap kali navigasi
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && e.target.href) {
                setTimeout(() => {
                    cleanupBackdrop();
                    cleanupBodyClass();
                    closeAllDropdowns();
                }, 100);
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
    
    <script>
    // Display SweetAlert from session
    @if (session('swal'))
        const swalData = @json(session('swal'));
        Swal.fire({
            icon: swalData.icon || 'info',
            title: swalData.title || 'Informasi',
            text: swalData.text || '',
            timer: swalData.timer || null,
            showConfirmButton: swalData.showConfirmButton !== false,
            confirmButtonColor: swalData.confirmButtonColor || '#3085d6'
        });
    @endif
    
    // Global function untuk konfirmasi kembali
    function confirmBack(url) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin kembali? Data yang telah dimasukkan tidak akan tersimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kembali',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
    
    // Global function untuk setup form confirmation
    function setupFormConfirmation(formId, message) {
        document.addEventListener('DOMContentLoaded', function() {
            let isSubmitting = false;
            const form = document.getElementById(formId);
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return;
                    }

                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isSubmitting = true;
                            form.submit();
                        }
                    });
                });
            }
        });
    }
    </script>
    
    @stack('scripts')
</body>
</html>

