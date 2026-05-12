<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pwaste (Aplikasi Pengelolaan Sampah Pelindo Subregional Kalimantan)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset('assets/img/bg-pelindo(1).jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            height: 100vh;
        }  
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .input-group {
            border-radius: 25px;
            overflow: hidden;
        }
        .input-group-text {
            background-color: #fff;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
        }
        .btn-login {
            border-radius: 25px;
            background: #003366;
        }
        .btn-login:hover {
            background: #0055aa;
        }
        .logo {
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="login-card p-4 text-center">
                    <div class="mb-4">
                        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Pelindo" class="logo" style="max-width: 100px;">
                            <img src="{{ asset('assets/img/pwaste.png') }}" alt="Logo PWaste" class="logo" style="max-width: 130px;">
                        </div>
                        <h2 class="mt-2 fs-5 text-primary">Aplikasi Pengelolaan Sampah Pelabuhan Banjarmasin</h2>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text">👤</span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">🔒</span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 py-2">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
