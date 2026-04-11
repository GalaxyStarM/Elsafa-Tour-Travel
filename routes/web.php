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

    // // Jamaah
    // Route::resource('jamaah', JamaahController::class);
    // Route::get('jamaah/{jamaah}/invoice', [JamaahController::class, 'invoice'])
    //     ->name('jamaah.invoice');

    // // Dokumen jamaah
    // Route::post('jamaah/{jamaah}/dokumen',          [JamaahController::class, 'uploadDokumen'])
    //     ->name('jamaah.dokumen.upload');
    // Route::delete('jamaah/{jamaah}/dokumen/{jenis}', [JamaahController::class, 'hapusDokumen'])
    //     ->name('jamaah.dokumen.hapus');

    // // Pembayaran
    // Route::post('jamaah/{jamaah}/pembayaran',    [PembayaranController::class, 'store'])
    //     ->name('pembayaran.store');
    // Route::put('pembayaran/{pembayaran}',        [PembayaranController::class, 'update'])
    //     ->name('pembayaran.update');
    // Route::delete('pembayaran/{pembayaran}',     [PembayaranController::class, 'destroy'])
    //     ->name('pembayaran.destroy');

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
