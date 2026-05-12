<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Petugas\AuthController;
use App\Http\Controllers\Api\V1\Petugas\SampahTerkelolaController;
use App\Http\Controllers\Api\V1\Petugas\SampahDiserahkanController;
use App\Http\Controllers\Api\V1\Master\MasterDataController;

Route::prefix('v1')->group(function () {

    // ==================== AUTH ====================
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // ==================== PUBLIC ====================
    Route::get('/master-data', [MasterDataController::class, 'index']);

    // ==================== PROTECTED ====================
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'getProfile']);

        // ==================== SAMPAH TERKELOLA ====================
        Route::prefix('sampah-terkelola')->group(function () {
            Route::get('/', [SampahTerkelolaController::class, 'index']);
            Route::get('/{id}', [SampahTerkelolaController::class, 'show']);
            Route::post('/', [SampahTerkelolaController::class, 'store']);
            Route::put('/{id}', [SampahTerkelolaController::class, 'update']);
            Route::delete('/{id}', [SampahTerkelolaController::class, 'destroy']); // ✅ tambahan
        });

        // ==================== SAMPAH DISERAHKAN ====================
        Route::prefix('sampah-diserahkan')->group(function () {
            Route::get('/', [SampahDiserahkanController::class, 'index']);
            Route::get('/{id}', [SampahDiserahkanController::class, 'show']);
            Route::post('/', [SampahDiserahkanController::class, 'store']);
            Route::put('/{id}', [SampahDiserahkanController::class, 'update']);
            Route::delete('/{id}', [SampahDiserahkanController::class, 'destroy']); // opsional
        });
    });
});
