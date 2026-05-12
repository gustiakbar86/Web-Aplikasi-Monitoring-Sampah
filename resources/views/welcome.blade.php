<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - Welcome</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            html, body {
                width: 100%;
                font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                color: #333;
            }
            
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            
            header {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding: 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .logo {
                font-size: 1.8rem;
                font-weight: bold;
                color: white;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
            
            nav {
                display: flex;
                gap: 1rem;
            }
            
            nav a {
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 500;
                color: white;
                background: rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.3);
                transition: all 0.3s ease;
                cursor: pointer;
            }
            
            nav a:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            
            main {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 3rem 2rem;
            }
            
            .content-wrapper {
                max-width: 1000px;
                width: 100%;
            }
            
            .welcome-section {
                text-align: center;
                margin-bottom: 3rem;
                color: white;
            }
            
            .welcome-section h1 {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                line-height: 1.2;
            }
            
            .welcome-section h1 span {
                color: #fbbf24;
            }
            
            .welcome-section p {
                font-size: 1.2rem;
                opacity: 0.95;
                max-width: 600px;
                margin: 0 auto;
                line-height: 1.6;
            }
            
            .cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
                margin-bottom: 3rem;
            }
            
            .card {
                background: rgba(255, 255, 255, 0.95);
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
                transition: all 0.3s ease;
            }
            
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 35px rgba(0, 0, 0, 0.2);
            }
            
            .card-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }
            
            .card h2 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
                color: #667eea;
            }
            
            .card p, .card ul {
                font-size: 0.95rem;
                line-height: 1.6;
                color: #555;
            }
            
            .card ul {
                list-style: none;
                padding: 0;
            }
            
            .card ul li {
                padding: 0.5rem 0;
                display: flex;
                align-items: center;
            }
            
            .card ul li::before {
                content: '✓';
                display: inline-block;
                width: 1.5rem;
                color: #10b981;
                font-weight: bold;
                margin-right: 0.5rem;
            }
            
            .button-group {
                display: flex;
                gap: 1.5rem;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 2rem;
            }
            
            .btn {
                padding: 1rem 2.5rem;
                font-size: 1rem;
                font-weight: 600;
                border: none;
                border-radius: 0.75rem;
                cursor: pointer;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                transition: all 0.3s ease;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
            }
            
            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            }
            
            .btn-secondary {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
            }
            
            .btn-secondary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
            }
            
            .btn-icon {
                width: 1.25rem;
                height: 1.25rem;
                stroke-width: 2;
            }
            
            footer {
                background: rgba(0, 0, 0, 0.3);
                color: rgba(255, 255, 255, 0.8);
                text-align: center;
                padding: 2rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 0.9rem;
            }
            
            @media (max-width: 768px) {
                .welcome-section h1 { font-size: 2.5rem; }
                .welcome-section p { font-size: 1rem; }
                header { flex-direction: column; gap: 1rem; padding: 1.5rem 1rem; }
                main { padding: 1.5rem 1rem; }
                .button-group { flex-direction: column; }
                .btn { width: 100%; justify-content: center; }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <header>
                <div class="logo">SIPESA</div>
                
                @if (Route::has('login'))
                    <nav>
                        @auth
                            @php
                                $dashboardRoute = 'login';
                                if (auth()->check()) {
                                    switch(auth()->user()->role) {
                                        case 1:
                                            $dashboardRoute = 'superadmin.dashboard';
                                            break;
                                        case 2:
                                            $dashboardRoute = 'admin.dashboard';
                                            break;
                                        case 3:
                                            $dashboardRoute = 'petugas.dashboard';
                                            break;
                                    }
                                }
                            @endphp
                            <a href="{{ route($dashboardRoute) }}">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}">Masuk</a>
                        @endauth
                    </nav>
                @endif
            </header>

            <!-- Main Content -->
            <main>
                <div class="content-wrapper">
                    <!-- Welcome Section -->
                    <section class="welcome-section">
                        <h1>Selamat Datang di <span>SIPESA</span></h1>
                        <p>Sistem Informasi Pengelolaan Sampah Pelabuhan - Solusi terintegrasi untuk manajemen sampah yang efisien dan berkelanjutan</p>
                    </section>

                    <!-- Description Cards -->
                    <section class="cards-grid">
                        <!-- About Application Card -->
                        <div class="card">
                            <div class="card-icon">⚡</div>
                            <h2>Tentang Aplikasi</h2>
                            <p>
                                SIPESA adalah platform terpadu yang dirancang khusus untuk memudahkan pengelolaan sampah di lingkungan pelabuhan. 
                                Aplikasi ini membantu dalam mencatat, melacak, dan mengelola sampah dari berbagai sumber dengan efisiensi maksimal.
                            </p>
                        </div>

                        <!-- Features Card -->
                        <div class="card">
                            <div class="card-icon">✨</div>
                            <h2>Fitur Unggulan</h2>
                            <ul>
                                <li>Dashboard real-time monitoring</li>
                                <li>Laporan terperinci dan analitik</li>
                                <li>Manajemen pengguna berbasis role</li>
                                <li>Sistem notifikasi terintegrasi</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Action Buttons -->
                    <div class="button-group">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3v1"></path>
                            </svg>
                            Masuk Sekarang
                        </a>

                        <a href="{{ route('preview-pedoman') }}" class="btn btn-secondary">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                            </svg>
                            Baca Panduan
                        </a>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; 2025 SIPESA Pelindo. Semua hak dilindungi.</p>
        </footer>
    </body>
</html>
