<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .error-box {
            background-color: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .error-code {
            font-size: 80px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-back {
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .btn-back i {
            margin-right: 10px;
        }
        
        .animated-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }
        
        .animated-bg span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: move 25s infinite;
            bottom: -150px;
        }
        
        .animated-bg span:nth-child(1) { left: 25%; animation-delay: 0s; }
        .animated-bg span:nth-child(2) { left: 10%; animation-delay: 2s; animation-duration: 10s; }
        .animated-bg span:nth-child(3) { left: 70%; animation-delay: 4s; }
        .animated-bg span:nth-child(4) { left: 40%; animation-delay: 0s; animation-duration: 18s; }
        .animated-bg span:nth-child(5) { left: 65%; animation-delay: 0s; }
        .animated-bg span:nth-child(6) { left: 75%; animation-delay: 3s; }
        .animated-bg span:nth-child(7) { left: 35%; animation-delay: 7s; }
        .animated-bg span:nth-child(8) { left: 50%; animation-delay: 15s; animation-duration: 45s; }
        .animated-bg span:nth-child(9) { left: 20%; animation-delay: 2s; animation-duration: 35s; }
        .animated-bg span:nth-child(10) { left: 85%; animation-delay: 0s; animation-duration: 11s; }
        
        @keyframes move {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="animated-bg">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="error-container">
        <div class="error-box">
            <div class="error-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="error-code">403</div>
            <h1 class="error-title">Akses Ditolak</h1>
            <p class="error-message">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Silakan kembali ke dashboard sesuai dengan role Anda.
            </p>
            
            @php
                $backUrl = '/';
                $backText = 'Kembali ke Beranda';
                
                if (auth()->check()) {
                    switch(auth()->user()->role) {
                        case 1:
                            $backUrl = route('superadmin.dashboard');
                            $backText = 'Kembali ke Dashboard Super Admin';
                            break;
                        case 2:
                            $backUrl = route('admin.dashboard');
                            $backText = 'Kembali ke Dashboard Admin';
                            break;
                        case 3:
                            $backUrl = route('petugas.dashboard');
                            $backText = 'Kembali ke Dashboard Petugas';
                            break;
                    }
                }
            @endphp
            
            <a href="{{ $backUrl }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                {{ $backText }}
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
