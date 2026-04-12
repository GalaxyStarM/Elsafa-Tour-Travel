<?php

// });
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\JamaahController;
use App\Http\Controllers\PembayaranController;

Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin protected
Route::middleware('admin.auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('jamaah')->name('jamaah.')->group(function () {
        Route::get('/',              [JamaahController::class, 'index'])->name('index');
        Route::get('/create',        [JamaahController::class, 'create'])->name('create');
        Route::post('/',             [JamaahController::class, 'store'])->name('store');
        Route::get('/{jamaah}',      [JamaahController::class, 'show'])->name('show');
        Route::get('/{jamaah}/edit', [JamaahController::class, 'edit'])->name('edit');
        Route::put('/{jamaah}',      [JamaahController::class, 'update'])->name('update');
        Route::delete('/{jamaah}',   [JamaahController::class, 'destroy'])->name('destroy');
    
        // Upload AJAX
        Route::post('/{jamaah}/upload-dokumen', [JamaahController::class, 'uploadDokumen'])->name('upload-dokumen');
        Route::post('/{jamaah}/upload-foto',    [JamaahController::class, 'uploadFoto'])->name('upload-foto');

        Route::delete('/{jamaah}/hapus-dokumen', [JamaahController::class, 'hapusDokumen'])->name('hapus-dokumen');
        Route::delete('/{jamaah}/hapus-foto',    [JamaahController::class, 'hapusFoto'])->name('hapus-foto');
    
        // Invoice
        Route::get('/{jamaah}/invoice', [JamaahController::class, 'invoice'])->name('invoice');
    
        // Pembayaran (nested)
        Route::prefix('/{jamaah}/pembayaran')->name('pembayaran.')->group(function () {
            Route::post('/',               [PembayaranController::class, 'store'])->name('store');
            Route::put('/{pembayaran}',    [PembayaranController::class, 'update'])->name('update');
            Route::delete('/{pembayaran}', [PembayaranController::class, 'destroy'])->name('destroy');
        });

    });

    // Data Mitra
    Route::get('/mitras',           [MitraController::class, 'index'])->name('mitras.index');
    Route::post('/mitras',          [MitraController::class, 'store'])->name('mitras.store');
    Route::get('/mitras/{mitra}',   [MitraController::class, 'show'])->name('mitras.show');
    Route::put('/mitras/{mitra}',   [MitraController::class, 'update'])->name('mitras.update');
    Route::delete('/mitras/{mitra}',[MitraController::class, 'destroy'])->name('mitras.destroy');

    // Paket
    Route::get('/pakets',           [PaketController::class, 'index'])->name('pakets.index');
    Route::post('/pakets',          [PaketController::class, 'store'])->name('pakets.store');
    Route::put('/pakets/{paket}',   [PaketController::class, 'update'])->name('pakets.update');
    Route::delete('/pakets/{paket}',[PaketController::class, 'destroy'])->name('pakets.destroy');
});
