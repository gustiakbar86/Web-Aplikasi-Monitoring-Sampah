<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'SIPESA Pelindo') - PWaste Pelindo Subregional Kalimantan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/petugas.css') }}">

    <style>
        :root {
            --primary-blue: #1E3F8C;
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
        }

        main {
            flex: 1;
        }

        /* Header Styling */
        .app-header {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 1rem;
        }

        .app-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .app-subtitle {
            font-size: 0.85rem;
            opacity: 0.9;
            margin: 0;
        }

        .header-right {
            text-align: right;
            color: var(--white);
        }

        .logout-btn {
            background-color: var(--white);
            color: var(--primary-blue);
            border: none;
            font-size: 0.75rem;
            padding: 2.5rem 1rem;
            line-height: 1.5;
            min-width: 0;
            border-radius: 4px;
            padding: 0.3rem 1rem;
            font-weight: 500;
        }

        /* Navbar Styling */
        .app-navbar {
            background-color: var(--primary-blue);
            padding: 0.35rem;
        }

        .app-navbar .btn {
            font-size: 14px !important;
            padding: 8px 16px !important;
            height: auto !important;
            line-height: 1.5 !important;
            white-space: nowrap;
            display: inline-flex !important;
            align-items: center;
        }

        .nav-pills .nav-link {
            color: var(--white);
            border-radius: 50px;
            margin: 0 0.2rem;
            padding: 0.4rem 1rem;
            display: flex;
            align-items: center;
        }

        .nav-pills .nav-link.active {
            background-color: var(--white);
            color: var(--primary-blue);
        }

        .nav-pills .nav-link:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Content Area */
        .content-area {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 1.5rem;
            margin: 1rem 0;
            min-height: 400px;
            border-radius: 4px;
        }

        /* Footer */
        .app-footer {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 23px;
            font-size: 0.9rem;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            margin-top: auto;
        }

        .app-footer p {
            font-size: 0.9rem;
        }

        /* Utils */
        .time-display {
            background-color: var(--white);
            color: var(--primary-blue);
            border-radius: 4px;
            padding: 0.3rem 1rem;
            display: inline-block;
            font-weight: 500;
        }

        .dropdown-toggle::after {
            vertical-align: middle;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-right {
                text-align: left;
                margin-top: 1rem;
            }
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
                padding: 0.6rem 1rem !important;
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

    <!-- Include Header -->
    @include('petugas.partials.header')

    <!-- Include Navigation -->
    @include('petugas.partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="app-footer w-100" style="background-color: #1E3F8C">
        <p class="mb-0 text-white">© 2025 Pelindo Subregional Banjarmasin - Aplikasi Pengelolaan Sampah</p>
    </footer>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Time Display Script -->
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }) + ' WITA';

            document.getElementById('timeDisplay').innerText = timeString;
        }

        // Update time every second
        setInterval(updateTime, 1000);

        // Initial update
        updateTime();
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/time.js') }}"></script>

    @stack('scripts')
</body>
</html>
