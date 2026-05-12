<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .error-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
            line-height: 1;
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 18px;
            color: #666666;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 500;
            color: #ffffff;
            background-color: #000000;
            text-decoration: none;
            border: 2px solid #000000;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background-color: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-message">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>
        
        @php
            $backRoute = 'login';
            $backText = 'Kembali ke Beranda';
            
            if (auth()->check()) {
                switch(auth()->user()->role) {
                    case 1:
                        $backRoute = 'superadmin.dashboard';
                        $backText = 'Kembali ke Dashboard';
                        break;
                    case 2:
                        $backRoute = 'admin.dashboard';
                        $backText = 'Kembali ke Dashboard';
                        break;
                    case 3:
                        $backRoute = 'petugas.dashboard';
                        $backText = 'Kembali ke Dashboard';
                        break;
                }
            }
        @endphp
        
        <a href="{{ route($backRoute) }}" class="btn-back">{{ $backText }}</a>
    </div>
</body>
</html>
