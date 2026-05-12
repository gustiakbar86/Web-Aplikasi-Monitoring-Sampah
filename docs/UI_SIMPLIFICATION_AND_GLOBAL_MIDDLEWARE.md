# Dokumentasi: Penyederhanaan UI dan Global Middleware

## Tanggal Update
**Tanggal:** 2025-01-XX

---

## 1. PENYEDERHANAAN TAMPILAN ERROR PAGES

### A. Perubahan pada 403.blade.php
**File:** `resources/views/errors/403.blade.php`

**Perubahan:**
- ❌ **DIHAPUS:** Bootstrap CDN, Font Awesome CDN
- ❌ **DIHAPUS:** Gradient background (`linear-gradient`)
- ❌ **DIHAPUS:** Animasi (shake, hover effect kompleks)
- ❌ **DIHAPUS:** Border-radius, box-shadow
- ✅ **DITAMBAHKAN:** Desain minimalis hitam putih
- ✅ **DITAMBAHKAN:** Background putih solid (#ffffff)
- ✅ **DITAMBAHKAN:** Text hitam (#000000)
- ✅ **DITAMBAHKAN:** Button hitam dengan hover sederhana
- ✅ **DITAMBAHKAN:** Dynamic routing berdasarkan role user

**Struktur Baru:**
```html
- Background: Putih (#ffffff)
- Error Code: 120px, bold, hitam
- Title: 32px, semibold, hitam
- Message: 18px, abu-abu (#666666)
- Button: Hitam dengan border, hover putih dengan border hitam
```

**Logic Redirect:**
```php
@php
    $backRoute = 'login';
    if (auth()->check()) {
        switch(auth()->user()->role) {
            case 1: $backRoute = 'superadmin.dashboard'; break;
            case 2: $backRoute = 'admin.dashboard'; break;
            case 3: $backRoute = 'petugas.dashboard'; break;
        }
    }
@endphp
```

### B. Perubahan pada unauthorized.blade.php
**File:** `resources/views/errors/unauthorized.blade.php`

**Perubahan yang sama dengan 403.blade.php:**
- ❌ **DIHAPUS:** Semua library eksternal (Bootstrap, Font Awesome)
- ❌ **DIHAPUS:** Gradient background purple/pink
- ❌ **DIHAPUS:** Animasi shake pada icon
- ❌ **DIHAPUS:** Efek visual kompleks
- ✅ **DITAMBAHKAN:** Desain minimalis identik dengan 403.blade.php
- ✅ **DITAMBAHKAN:** Dynamic routing sesuai role

**Tujuan:**
Halaman ini ditampilkan ketika user sudah login tetapi mencoba mengakses halaman yang tidak sesuai dengan role-nya (misal: Admin mencoba akses halaman SuperAdmin).

---

## 2. GLOBAL MIDDLEWARE UNTUK URL SIPESAPELINDO

### A. CheckSipesaPelindoUrl Middleware
**File:** `app/Http/Middleware/CheckSipesaPelindoUrl.php`

**Fungsi:**
Menangkap semua request ke URL yang dimulai dengan prefix `sipesapelindo` tetapi tidak ditemukan (404), lalu redirect ke halaman 403.

**Logic:**
```php
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);
    
    // Jika response adalah 404 dan URL dimulai dengan 'sipesapelindo'
    if ($response->getStatusCode() === 404 && str_starts_with($request->path(), 'sipesapelindo')) {
        return response()->view('errors.403', [], 403);
    }
    
    return $response;
}
```

**Contoh Penggunaan:**
- ❌ URL: `http://localhost/sipesapelindo/halaman-tidak-ada` → Redirect ke 403
- ❌ URL: `http://localhost/sipesapelindo/admin/xyz` → Redirect ke 403
- ✅ URL: `http://localhost/superadmin/dashboard` → Normal (tidak dimulai dengan 'sipesapelindo')
- ✅ URL: `http://localhost/admin/dashboard` → Normal

### B. Registrasi Global Middleware
**File:** `bootstrap/app.php`

**Perubahan:**
```php
->withMiddleware(function (Middleware $middleware): void {
    // Register middleware aliases
    $middleware->alias([
        'superadmin' => \App\Http\Middleware\AuthUserMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'petugas' => \App\Http\Middleware\PetugasMiddleware::class,
    ]);
    
    // Register global middleware to check sipesapelindo URLs
    $middleware->append(\App\Http\Middleware\CheckSipesaPelindoUrl::class);
})
```

**Penjelasan:**
- `$middleware->append()` menambahkan middleware ke global middleware stack
- Middleware ini akan dijalankan pada **SEMUA** request HTTP
- Dieksekusi setelah route matching selesai (post-middleware)

---

## 3. KONVERSI URL KE ROUTE NAME

### A. Perubahan pada welcome.blade.php
**File:** `resources/views/welcome.blade.php`

**Sebelum:**
```html
<a href="{{ url('/dashboard') }}">Dashboard</a>
```

**Sesudah:**
```php
@php
    $dashboardRoute = 'login';
    if (auth()->check()) {
        switch(auth()->user()->role) {
            case 1: $dashboardRoute = 'superadmin.dashboard'; break;
            case 2: $dashboardRoute = 'admin.dashboard'; break;
            case 3: $dashboardRoute = 'petugas.dashboard'; break;
        }
    }
@endphp
<a href="{{ route($dashboardRoute) }}">Dashboard</a>
```

**Tujuan:**
- Menggunakan `route()` helper dengan route name
- Dynamic routing berdasarkan role user yang login
- Lebih maintainable jika URL prefix berubah

### B. Verifikasi File Blade Lainnya
Semua file blade lain sudah menggunakan `route()` helper dengan benar:
- ✅ Error pages menggunakan `route('login')`, `route('superadmin.dashboard')`, dll
- ✅ Tidak ada penggunaan `url('/superadmin')`, `url('/admin')`, dll
- ✅ Asset files tetap menggunakan `asset()` helper (tidak perlu diubah)

---

## 4. TESTING

### A. Test Error Pages
**Test Case 1: 403 Page**
1. Logout dari sistem
2. Akses URL: `http://localhost/superadmin/dashboard`
3. **Expected:** Redirect ke 403.blade.php dengan desain minimalis hitam putih
4. Klik tombol "Kembali ke Beranda" → Redirect ke login

**Test Case 2: Unauthorized Page**
1. Login sebagai Admin (role 2)
2. Akses URL: `http://localhost/superadmin/dashboard`
3. **Expected:** Redirect ke unauthorized.blade.php dengan desain minimalis
4. Klik tombol "Kembali ke Dashboard Admin" → Redirect ke admin dashboard

### B. Test Global Middleware
**Test Case 3: Invalid sipesapelindo URL**
1. Akses URL: `http://localhost/sipesapelindo/halaman-tidak-ada`
2. **Expected:** Tampil halaman 403.blade.php
3. Klik tombol back → Redirect sesuai role

**Test Case 4: Valid URL (bukan sipesapelindo)**
1. Akses URL: `http://localhost/halaman-tidak-ada`
2. **Expected:** Tampil Laravel default 404 page (BUKAN redirect ke 403)

### C. Test Route Names
**Test Case 5: Dashboard Link**
1. Login sebagai SuperAdmin
2. Akses welcome page (/)
3. Klik link "Dashboard"
4. **Expected:** Redirect ke `http://localhost/superadmin/dashboard`

**Test Case 6: Error Page Back Button**
1. Trigger error 403 atau unauthorized
2. Klik tombol back
3. **Expected:** Redirect ke dashboard sesuai role user

---

## 5. KEUNTUNGAN PERUBAHAN INI

### A. UI Simplification
- ✅ **Performance:** Tidak ada loading CDN eksternal (Bootstrap, Font Awesome)
- ✅ **Minimalis:** Desain lebih clean dan fokus pada informasi
- ✅ **Konsisten:** Semua error page punya desain yang sama
- ✅ **Accessible:** Lebih mudah dibaca dengan kontras hitam putih

### B. Global Middleware
- ✅ **Security:** Mencegah akses ke URL yang tidak terdaftar dengan prefix tertentu
- ✅ **User Experience:** User tidak melihat Laravel default 404 untuk URL sipesapelindo
- ✅ **Maintainable:** Mudah menambah logic validasi URL di satu tempat

### C. Route Names
- ✅ **Maintainable:** Jika URL prefix berubah, hanya perlu update routes/web.php
- ✅ **Type Safe:** Laravel akan error jika route name tidak ada
- ✅ **Clear Intent:** Lebih jelas maksudnya redirect kemana

---

## 6. STRUKTUR MIDDLEWARE SYSTEM

### Middleware Hierarchy
```
Global Middleware
├── CheckSipesaPelindoUrl (Global - untuk semua request)
│
Route-Specific Middleware
├── AuthUserMiddleware (alias: 'superadmin') - Role 1
├── AdminMiddleware (alias: 'admin') - Role 2
└── PetugasMiddleware (alias: 'petugas') - Role 3
```

### Alur Request
```
User Request
    ↓
Global Middleware (CheckSipesaPelindoUrl)
    ↓
Route Matching
    ↓
Route-Specific Middleware (superadmin/admin/petugas)
    ↓
Controller
    ↓
Response
    ↓
Global Middleware (Check 404 + sipesapelindo prefix)
    ↓
Send Response to User
```

---

## 7. TROUBLESHOOTING

### Problem: Middleware tidak jalan
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Problem: Route name not found
**Solution:**
Cek di `routes/web.php` apakah route sudah punya `->name('xxx')`

### Problem: 403 page masih punya animasi
**Solution:**
Clear browser cache atau hard refresh (Ctrl + Shift + R)

---

## 8. FILE YANG DIUBAH

1. ✅ `resources/views/errors/403.blade.php` - Simplified UI
2. ✅ `resources/views/errors/unauthorized.blade.php` - Simplified UI
3. ✅ `app/Http/Middleware/CheckSipesaPelindoUrl.php` - New middleware
4. ✅ `bootstrap/app.php` - Register global middleware
5. ✅ `resources/views/welcome.blade.php` - Use route names
6. ✅ `app/Http/Controllers/SuperAdmin/LaporanController.php` - Fix syntax error

---

## 9. NEXT STEPS (OPTIONAL)

### Improvement Ideas:
1. **Custom 404 Page:** Buat custom 404.blade.php dengan desain minimalis yang sama
2. **Error Logging:** Log semua request ke URL sipesapelindo yang tidak ada
3. **Analytics:** Track berapa kali user hit invalid URLs
4. **Rate Limiting:** Add rate limiting untuk mencegah brute force URL guessing

---

**Dokumentasi dibuat oleh:** GitHub Copilot  
**Untuk proyek:** SIPESA Pelindo
