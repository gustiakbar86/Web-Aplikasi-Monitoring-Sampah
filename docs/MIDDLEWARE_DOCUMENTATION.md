# Sistem Middleware Role-Based Access Control (RBAC)

## Daftar Role
1. **Role 1** - Super Admin
2. **Role 2** - Admin
3. **Role 3** - Petugas

## Middleware yang Tersedia

### 1. SuperAdminMiddleware (Role 1)
**File**: `app/Http/Middleware/AuthUserMiddleware.php`
**Alias**: `superadmin`

**Fungsi**:
- Mengecek apakah user sudah login
- Mengecek apakah role user = 1 (Super Admin)
- Jika belum login: redirect ke halaman beranda (`/`)
- Jika role bukan 1: redirect ke halaman unauthorized

**Penggunaan**:
```php
Route::middleware('superadmin')->group(function () {
    // Routes untuk Super Admin
});
```

### 2. AdminMiddleware (Role 2)
**File**: `app/Http/Middleware/AdminMiddleware.php`
**Alias**: `admin`

**Fungsi**:
- Mengecek apakah user sudah login
- Mengecek apakah role user = 2 (Admin)
- Jika belum login: redirect ke halaman beranda (`/`)
- Jika role bukan 2: redirect ke halaman unauthorized

**Penggunaan**:
```php
Route::middleware('admin')->group(function () {
    // Routes untuk Admin
});
```

### 3. PetugasMiddleware (Role 3)
**File**: `app/Http/Middleware/PetugasMiddleware.php`
**Alias**: `petugas`

**Fungsi**:
- Mengecek apakah user sudah login
- Mengecek apakah role user = 3 (Petugas)
- Jika belum login: redirect ke halaman beranda (`/`)
- Jika role bukan 3: redirect ke halaman unauthorized

**Penggunaan**:
```php
Route::middleware('petugas')->group(function () {
    // Routes untuk Petugas
});
```

## Halaman Error

### 1. Halaman Unauthorized (403)
**Route**: `/unauthorized`
**View**: `resources/views/errors/unauthorized.blade.php`

**Fitur**:
- Menampilkan pesan error 403
- Tombol kembali yang dinamis sesuai role user:
  - Role 1: Kembali ke Dashboard Super Admin
  - Role 2: Kembali ke Dashboard Admin
  - Role 3: Kembali ke Dashboard Petugas
  - Tidak login: Kembali ke Beranda
- Animasi background yang menarik
- Responsive design

### 2. Halaman 403 Standard
**View**: `resources/views/errors/403.blade.php`

Halaman error 403 standar Laravel untuk kasus lainnya.

## Registrasi Middleware

Middleware telah diregistrasi di `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'superadmin' => \App\Http\Middleware\AuthUserMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'petugas' => \App\Http\Middleware\PetugasMiddleware::class,
    ]);
})
```

## Implementasi di Routes

### SuperAdmin Routes
```php
Route::prefix('superadmin')->name('superadmin.')->middleware('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    // ... routes lainnya
});
```

### Admin Routes
```php
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // ... routes lainnya
});
```

### Petugas Routes
```php
Route::prefix('petugas')->name('petugas.')->middleware('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    // ... routes lainnya
});
```

## Alur Kerja Middleware

```
1. User mengakses halaman
   ↓
2. Middleware mengecek: Apakah user sudah login?
   ├─ Tidak → Redirect ke '/' dengan pesan error
   └─ Ya → Lanjut ke step 3
      ↓
3. Middleware mengecek: Apakah role sesuai?
   ├─ Tidak → Redirect ke '/unauthorized' dengan pesan error
   └─ Ya → Akses diberikan, lanjut ke halaman
```

## Cara Testing

### Test 1: Akses tanpa login
1. Buka browser dalam mode incognito
2. Akses: `http://localhost:8000/superadmin/dashboard`
3. **Expected**: Redirect ke `/` dengan pesan "Silakan login terlebih dahulu."

### Test 2: Akses dengan role yang salah
1. Login sebagai Petugas (role 3)
2. Akses: `http://localhost:8000/admin/dashboard`
3. **Expected**: Redirect ke `/unauthorized` dengan tombol kembali ke Dashboard Petugas

### Test 3: Akses dengan role yang benar
1. Login sebagai Admin (role 2)
2. Akses: `http://localhost:8000/admin/dashboard`
3. **Expected**: Halaman dashboard admin muncul

## Customization

### Mengubah Redirect Destination
Edit file middleware yang sesuai, misalnya untuk SuperAdmin:

```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
    }

    if (auth()->user()->role != 1) {
        return redirect()->route('custom.error.page');
    }

    return $next($request);
}
```

### Menambahkan Multiple Role
Jika ingin satu middleware untuk multiple role:

```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!auth()->check()) {
        return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
    }

    if (!in_array(auth()->user()->role, $roles)) {
        return redirect()->route('unauthorized');
    }

    return $next($request);
}
```

Penggunaan:
```php
Route::middleware('role:1,2')->group(function () {
    // Akses untuk role 1 dan 2
});
```

## Troubleshooting

### Middleware tidak bekerja
1. Clear cache: `php artisan route:clear && php artisan cache:clear`
2. Pastikan middleware sudah diregistrasi di `bootstrap/app.php`
3. Cek struktur database: pastikan tabel `users` memiliki kolom `role`

### Redirect loop
1. Pastikan route `/` dan `/unauthorized` tidak memiliki middleware
2. Cek logic di middleware, pastikan tidak ada circular redirect

### Session tidak tersimpan
1. Pastikan session config sudah benar
2. Clear session: `php artisan session:clear`
3. Restart development server

## Security Best Practices

1. ✅ **Selalu validasi di backend** - Jangan hanya mengandalkan frontend
2. ✅ **Gunakan middleware di semua protected routes**
3. ✅ **Log unauthorized access attempts**
4. ✅ **Implementasi rate limiting untuk mencegah brute force**
5. ✅ **Gunakan HTTPS di production**

## Update Log

- **2025-11-19**: Initial implementation
  - Created 3 middleware (SuperAdmin, Admin, Petugas)
  - Created unauthorized page with dynamic redirect
  - Registered middleware aliases
  - Applied middleware to all route groups
