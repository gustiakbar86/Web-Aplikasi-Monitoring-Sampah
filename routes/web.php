<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardStatsController;
use App\Http\Controllers\User\PetugasController;
use App\Http\Controllers\User\SuperAdminController;
use App\Http\Controllers\User\AdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\SuperAdmin\LaporanController as SuperAdminLaporanController;
use App\Http\Controllers\SuperAdmin\DokumenController as SuperAdminDokumenController;
use App\Http\Controllers\SuperAdmin\LokasiAsalController as SuperAdminLokasiAsalController;
use App\Http\Controllers\SuperAdmin\JenisController as SuperAdminJenisController;
use App\Http\Controllers\SuperAdmin\TujuanSampahController as SuperAdminTujuanSampahController;
use App\Http\Controllers\SuperAdmin\SampahTerkelolaController as SuperAdminSampahTerkelolaController;
use App\Http\Controllers\SuperAdmin\SampahDiserahkanController as SuperAdminSampahDiserahkanController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/preview-pedoman', function () {
    return view('preview-pedoman');
})->name('preview-pedoman');

Route::get('/pedoman/stream', function () {
    $pdfPath = public_path('assets/docs/Buku Petunjuk Penggunaan Aplikasi P-Waste Pelabuhan Banjarmasin.pdf');
    
    // Jika file tidak ada, kembalikan response dengan pesan
    if (!file_exists($pdfPath)) {
        return response()->json([
            'message' => 'File pedoman tidak ditemukan di ' . $pdfPath,
        ], 404);
    }
    
    return response()->file($pdfPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="Buku Petunjuk Penggunaan Aplikasi P-Waste Pelabuhan Banjarmasin.pdf"',
    ]);
})->name('pedoman.stream');

// Unauthorized route
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');

// Authentication routes
Route::get('/login', [AuthController::class, 'FormLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SuperAdmin routes (Role 1)
Route::prefix('superadmin')->name('superadmin.')->middleware('superadmin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Master Data routes
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users');
        Route::get('/sampah-terkelola', [SuperAdminSampahTerkelolaController::class, 'index'])->name('sampah-terkelola');
        Route::get('/sampah-diserahkan', [SuperAdminSampahDiserahkanController::class, 'index'])->name('sampah-diserahkan');
        Route::get('/lokasi-asal', [SuperAdminLokasiAsalController::class, 'index'])->name('lokasi-asal');
        Route::get('/jenis-sampah', [SuperAdminJenisController::class, 'index'])->name('jenis-sampah');
        Route::get('/tujuan-sampah', [SuperAdminTujuanSampahController::class, 'index'])->name('tujuan-sampah');
        Route::get('/dokumen', [SuperAdminDokumenController::class, 'index'])->name('dokumen');
        
        // CRUD Instansi
        Route::get('/instansi', [SuperAdminController::class, 'masterInstansi'])->name('instansi');
        Route::get('/instansi/create', [SuperAdminController::class, 'createInstansi'])->name('instansi.create');
        Route::post('/instansi', [SuperAdminController::class, 'storeInstansi'])->name('instansi.store');
        Route::get('/instansi/{id}/edit', [SuperAdminController::class, 'editInstansi'])->name('instansi.edit');
        Route::put('/instansi/{id}', [SuperAdminController::class, 'updateInstansi'])->name('instansi.update');
        Route::delete('/instansi/{id}', [SuperAdminController::class, 'deleteInstansi'])->name('instansi.destroy');
        
        // CRUD Dokumen
        Route::get('/dokumen/create', [SuperAdminDokumenController::class, 'create'])->name('dokumen.create');
        Route::post('/dokumen', [SuperAdminDokumenController::class, 'store'])->name('dokumen.store');
        Route::get('/dokumen/{id}/edit', [SuperAdminDokumenController::class, 'edit'])->name('dokumen.edit');
        Route::put('/dokumen/{id}', [SuperAdminDokumenController::class, 'update'])->name('dokumen.update');
        Route::delete('/dokumen/{id}', [SuperAdminDokumenController::class, 'destroy'])->name('dokumen.destroy');
        
        // CRUD Lokasi Asal
        Route::get('/lokasi-asal/create', [SuperAdminLokasiAsalController::class, 'create'])->name('lokasi-asal.create');
        Route::post('/lokasi-asal', [SuperAdminLokasiAsalController::class, 'store'])->name('lokasi-asal.store');
        Route::get('/lokasi-asal/{id_lokasi}/edit', [SuperAdminLokasiAsalController::class, 'edit'])->name('lokasi-asal.edit');
        Route::put('/lokasi-asal/{id_lokasi}', [SuperAdminLokasiAsalController::class, 'update'])->name('lokasi-asal.update');
        Route::delete('/lokasi-asal/{id_lokasi}', [SuperAdminLokasiAsalController::class, 'destroy'])->name('lokasi-asal.destroy');
        
        // CRUD Jenis Sampah
        Route::get('/jenis/create', [SuperAdminJenisController::class, 'create'])->name('jenis.create');
        Route::post('/jenis', [SuperAdminJenisController::class, 'store'])->name('jenis.store');
        Route::get('/jenis/{id_jenis}/edit', [SuperAdminJenisController::class, 'edit'])->name('jenis.edit');
        Route::put('/jenis/{id_jenis}', [SuperAdminJenisController::class, 'update'])->name('jenis.update');
        Route::delete('/jenis/{id_jenis}', [SuperAdminJenisController::class, 'destroy'])->name('jenis.destroy');
        
        // CRUD Tujuan Sampah
        Route::get('/tujuan/create', [SuperAdminTujuanSampahController::class, 'create'])->name('tujuan.create');
        Route::post('/tujuan', [SuperAdminTujuanSampahController::class, 'store'])->name('tujuan.store');
        Route::get('/tujuan/{id_tujuan}/edit', [SuperAdminTujuanSampahController::class, 'edit'])->name('tujuan.edit');
        Route::put('/tujuan/{id_tujuan}', [SuperAdminTujuanSampahController::class, 'update'])->name('tujuan.update');
        Route::delete('/tujuan/{id_tujuan}', [SuperAdminTujuanSampahController::class, 'destroy'])->name('tujuan.destroy');
        
        // CRUD Sampah Terkelola
        Route::get('/sampah-terkelola/create', [SuperAdminSampahTerkelolaController::class, 'create'])->name('sampah-terkelola.create');
        Route::post('/sampah-terkelola', [SuperAdminSampahTerkelolaController::class, 'store'])->name('sampah-terkelola.store');
        Route::get('/sampah-terkelola/{id}', [SuperAdminSampahTerkelolaController::class, 'show'])->name('sampah-terkelola.show');
        Route::get('/sampah-terkelola/{id}/edit', [SuperAdminSampahTerkelolaController::class, 'edit'])->name('sampah-terkelola.edit');
        Route::put('/sampah-terkelola/{id}', [SuperAdminSampahTerkelolaController::class, 'update'])->name('sampah-terkelola.update');
        Route::delete('/sampah-terkelola/{id}', [SuperAdminSampahTerkelolaController::class, 'destroy'])->name('sampah-terkelola.destroy');
        
        // CRUD Sampah Diserahkan
        Route::get('/sampah-diserahkan/create', [SuperAdminSampahDiserahkanController::class, 'create'])->name('sampah-diserahkan.create');
        Route::post('/sampah-diserahkan', [SuperAdminSampahDiserahkanController::class, 'store'])->name('sampah-diserahkan.store');
        Route::get('/sampah-diserahkan/{id}', [SuperAdminSampahDiserahkanController::class, 'show'])->name('sampah-diserahkan.show');
        Route::get('/sampah-diserahkan/{id}/edit', [SuperAdminSampahDiserahkanController::class, 'edit'])->name('sampah-diserahkan.edit');
        Route::put('/sampah-diserahkan/{id}', [SuperAdminSampahDiserahkanController::class, 'update'])->name('sampah-diserahkan.update');
        Route::delete('/sampah-diserahkan/{id}', [SuperAdminSampahDiserahkanController::class, 'destroy'])->name('sampah-diserahkan.destroy');
        
        // CRUD Users
        Route::get('/users/create', [SuperAdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [SuperAdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [SuperAdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [SuperAdminUserController::class, 'destroy'])->name('users.destroy');
    });

    // Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [SuperAdminLaporanController::class, 'index'])->name('index');
        
        // Export Laporan menggunakan template (Tahunan & Bulanan Multi-Select)
        Route::get('/export-baru', [SuperAdminLaporanController::class, 'showExportForm'])->name('export-baru');
        Route::post('/export-baru', [SuperAdminLaporanController::class, 'processExport'])->name('export-baru.process');
        
        // Laporan Harian, Mingguan, Bulanan, Tahunan
        Route::get('/harian', [SuperAdminLaporanController::class, 'laporanHarian'])->name('harian');
        Route::get('/mingguan', [SuperAdminLaporanController::class, 'laporanMingguan'])->name('mingguan');
        Route::get('/bulanan', [SuperAdminLaporanController::class, 'laporanBulanan'])->name('bulanan');
        Route::get('/tahunan', [SuperAdminLaporanController::class, 'laporanTahunan'])->name('tahunan');
    });
});

// Admin routes (Role 2)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Petugas
    Route::prefix('kelola')->name('kelola.')->group(function () {
        Route::get('/petugas', [AdminController::class, 'kelolaPetugas'])->name('petugas');
    });

    // Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        
        // Export Laporan menggunakan template (Tahunan & Bulanan Multi-Select)
        Route::get('/export-baru', [LaporanController::class, 'showExportForm'])->name('export-baru');
        Route::post('/export-baru', [LaporanController::class, 'processExport'])->name('export-baru.process');
    });
    Route::get('/kelola-petugas', [AdminController::class, 'kelolaPetugas'])->name('kelola-petugas');
    Route::get('/tambah-petugas', [AdminController::class, 'tambahPetugas'])->name('tambah-petugas');
    Route::post('/store-petugas', [AdminController::class, 'storePetugas'])->name('store-petugas');
    Route::get('/edit-petugas/{id}', [AdminController::class, 'editPetugas'])->name('edit-petugas');
    Route::put('/update-petugas/{id}', [AdminController::class, 'updatePetugas'])->name('update-petugas');
    Route::delete('/delete-petugas/{id}', [AdminController::class, 'deletePetugas'])->name('delete-petugas');

    // Admin tidak memiliki akses input sampah

    // Tabulasi Data (Admin dapat melihat, input, dan edit)
    Route::prefix('data')->name('data.')->group(function() {
        // Sampah Terkelola
        Route::get('/sampah-terkelola', [AdminController::class, 'dataSampahTerkelola'])->name('sampah-terkelola');
        Route::get('/sampah-terkelola/create', [AdminController::class, 'createSampahTerkelola'])->name('sampah-terkelola.create');
        Route::post('/sampah-terkelola/store', [AdminController::class, 'storeSampahTerkelola'])->name('sampah-terkelola.store');
        Route::get('/sampah-terkelola/{id}', [AdminController::class, 'showSampahTerkelola'])->name('sampah-terkelola.show');
        Route::get('/sampah-terkelola/{id}/edit', [AdminController::class, 'editSampahTerkelola'])->name('sampah-terkelola.edit');
        Route::put('/sampah-terkelola/{id}/update', [AdminController::class, 'updateSampahTerkelola'])->name('sampah-terkelola.update');

        // Sampah Diserahkan
        Route::get('/sampah-diserahkan', [AdminController::class, 'dataSampahDiserahkan'])->name('sampah-diserahkan');
        Route::get('/sampah-diserahkan/create', [AdminController::class, 'createSampahDiserahkan'])->name('sampah-diserahkan.create');
        Route::post('/sampah-diserahkan/store', [AdminController::class, 'storeSampahDiserahkan'])->name('sampah-diserahkan.store');
        Route::get('/sampah-diserahkan/{id}', [AdminController::class, 'showSampahDiserahkan'])->name('sampah-diserahkan.show');
        Route::get('/sampah-diserahkan/{id}/edit', [AdminController::class, 'editSampahDiserahkan'])->name('sampah-diserahkan.edit');
        Route::put('/sampah-diserahkan/{id}/update', [AdminController::class, 'updateSampahDiserahkan'])->name('sampah-diserahkan.update');

        // Dokumen (view only) - old route kept for compatibility
        Route::get('/dokumen', [AdminController::class, 'dataDokumen'])->name('dokumen');
    });

    // Kelola Dokumen (Admin dapat CRUD dokumen)
    Route::prefix('dokumen')->name('dokumen.')->group(function() {
        Route::get('/', [AdminController::class, 'dokumenIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'dokumenCreate'])->name('create');
        Route::post('/store', [AdminController::class, 'dokumenStore'])->name('store');
        Route::get('/edit/{id}', [AdminController::class, 'dokumenEdit'])->name('edit');
        Route::put('/update/{id}', [AdminController::class, 'dokumenUpdate'])->name('update');
        Route::delete('/destroy/{id}', [AdminController::class, 'dokumenDestroy'])->name('destroy');
    });

    // Master Data
    Route::prefix('master')->name('master.')->group(function() {
        Route::get('/lokasi-asal', [AdminController::class, 'masterLokasiAsal'])->name('lokasi-asal');
        Route::get('/jenis-sampah', [AdminController::class, 'masterJenisSampah'])->name('jenis-sampah');
        Route::get('/tujuan-sampah', [AdminController::class, 'masterTujuanSampah'])->name('tujuan-sampah');

        // CRUD untuk lokasi asal
        Route::post('/lokasi-asal/store', [AdminController::class, 'storeLokasiAsal'])->name('lokasi-asal.store');
        Route::put('/lokasi-asal/{id}/update', [AdminController::class, 'updateLokasiAsal'])->name('lokasi-asal.update');
        Route::delete('/lokasi-asal/{id}/delete', [AdminController::class, 'deleteLokasiAsal'])->name('lokasi-asal.delete');

        // CRUD untuk jenis sampah
        Route::post('/jenis-sampah/store', [AdminController::class, 'storeJenisSampah'])->name('jenis-sampah.store');
        Route::put('/jenis-sampah/{id}/update', [AdminController::class, 'updateJenisSampah'])->name('jenis-sampah.update');
        Route::delete('/jenis-sampah/{id}/delete', [AdminController::class, 'deleteJenisSampah'])->name('jenis-sampah.delete');

        // CRUD untuk tujuan sampah
        Route::post('/tujuan-sampah/store', [AdminController::class, 'storeTujuanSampah'])->name('tujuan-sampah.store');
        Route::put('/tujuan-sampah/{id}/update', [AdminController::class, 'updateTujuanSampah'])->name('tujuan-sampah.update');
        Route::delete('/tujuan-sampah/{id}/delete', [AdminController::class, 'deleteTujuanSampah'])->name('tujuan-sampah.delete');
    });
});

// Petugas routes (Role 3)
Route::prefix('petugas')->name('petugas.')->middleware('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistik', [PetugasController::class, 'getStatistik'])->name('statistik');

    // Halaman data sampah
    Route::get('/sampah-terkelola', [PetugasController::class, 'sampahTerkelola'])->name('sampah-terkelola');
    Route::get('/sampah-diserahkan', [PetugasController::class, 'sampahDiserahkan'])->name('sampah-diserahkan');

    // Halaman input data
    Route::get('/input-sampah-terkelola', [PetugasController::class, 'inputSampahTerkelola'])->name('input-sampah-terkelola');
    Route::post('/store-sampah-terkelola', [PetugasController::class, 'storeSampahTerkelola'])->name('store-sampah-terkelola');
    Route::get('/input-sampah-diserahkan', [PetugasController::class, 'inputSampahDiserahkan'])->name('input-sampah-diserahkan');
    Route::post('/store-sampah-diserahkan', [PetugasController::class, 'storeSampahDiserahkan'])->name('store-sampah-diserahkan');
    
    // Edit data sampah
    Route::get('/edit-sampah-terkelola/{id}', [PetugasController::class, 'editSampahTerkelola'])->name('edit-sampah-terkelola');
    Route::put('/update-sampah-terkelola/{id}', [PetugasController::class, 'updateSampahTerkelola'])->name('update-sampah-terkelola');
    Route::get('/edit-sampah-diserahkan/{id}', [PetugasController::class, 'editSampahDiserahkan'])->name('edit-sampah-diserahkan');
    Route::put('/update-sampah-diserahkan/{id}', [PetugasController::class, 'updateSampahDiserahkan'])->name('update-sampah-diserahkan');
});
