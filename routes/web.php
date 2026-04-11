<?php

use App\Http\Controllers\MitraController;
use App\Http\Controllers\PaketController;

// --- Tambahkan route ini di dalam group middleware ---
// Route::middleware(['auth', 'admin'])->group(function () {

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

// });