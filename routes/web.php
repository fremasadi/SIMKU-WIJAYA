<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\GajiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('bahan-baku', BahanBakuController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('pembelian', PembelianController::class);
    Route::resource('produksi', ProduksiController::class);
    Route::resource('penjualan', PenjualanController::class);

    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/update-status', [PresensiController::class, 'updateStatus'])->name('presensi.updateStatus');

    Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
    Route::get('/gaji/generate', [GajiController::class, 'generate'])->name('gaji.generate');
    Route::patch('/gaji/{gaji}/bayar', [GajiController::class, 'markAsPaid'])->name('gaji.bayar');
});

require __DIR__.'/auth.php';
