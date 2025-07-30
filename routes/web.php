<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\AnalisaController;
use App\Http\Controllers\PersetujuanController;

Auth::routes();
Route::get('/', fn() => redirect('/login'));
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboards');
});
Route::middleware(['auth','Pimpinan'])->group(function () {
    Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
    Route::get('/persetujuan/show/{id}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
    Route::post('/persetujuan/delete', [PersetujuanController::class, 'hapus'])->name('persetujuan.hapus');
    Route::post('/persetujuan/legalstore', [PersetujuanController::class, 'legalstore'])->name('persetujuan.legalstore');
    Route::get('/persetujuan/{id}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
});
Route::middleware(['auth','MarketingOrPimpinan'])->group(function () {
    Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');
    Route::get('/nasabah/tambah', [NasabahController::class, 'create'])->name('nasabah.create');
    Route::post('/nasabah/store', [NasabahController::class, 'store'])->name('nasabah.store');
    Route::post('/nasabah/upload', [NasabahController::class, 'upload'])->name('nasabah.upload');
    Route::get('/nasabah/{nasabah}/edit', [NasabahController::class, 'edit'])->name('nasabah.edit');
    Route::put('/nasabah/{nasabah}', [NasabahController::class, 'update'])->name('nasabah.update');
    Route::post('/nasabah/delete', [NasabahController::class, 'hapus'])->name('nasabah.destroy');
    Route::get('/nasabah/fetch/{nik}', [NasabahController::class, 'fetchByNIK']);
    Route::post('/nasabah/hapus', [NasabahController::class, 'hapus'])->name('nasabah.hapus');
    Route::get('/klasifikasi/tambah', [KlasifikasiController::class, 'create'])->name('klasifikasi.create');
    Route::post('/klasifikasi/hitung', [KlasifikasiController::class, 'hitung'])->name('klasifikasi.hitung');
    Route::post('/klasifikasi/store', [KlasifikasiController::class, 'store'])->name('klasifikasi.store');
    Route::get('/klasifikasi', [KlasifikasiController::class, 'index'])->name('klasifikasi.index');
    Route::get('/klasifikasi/hasil/{data}', [KlasifikasiController::class, 'hasil'])->name('klasifikasi.hasil');
    Route::post('/klasifikasi/delete', [KlasifikasiController::class, 'hapus'])->name('klasifikasi.deleteSelected');
    Route::get('/analisa', [AnalisaController::class, 'index'])->name('analisa.index');
    Route::get('/analisa/fetch/{nik}', [AnalisaController::class, 'fetchByNIK']);
    Route::post('/analisa/store', [AnalisaController::class, 'store'])->name('analisa.store');
});
Route::middleware(['auth', 'LegalOrPimpinan'])->group(function () { 
    Route::get('/legal', [LegalController::class, 'index'])->name('legal.index');
    Route::get('/legal/{id}/edit', [LegalController::class, 'edit'])->name('legal.edit');
    Route::post('/legal/verifikasi', [LegalController::class, 'verifikasi'])->name('legal.verifikasi');
    Route::post('/legal/hapus', [LegalController::class, 'hapus'])->name('legal.hapus');
    Route::get('/legal/{legal}/view-file/{field}', [LegalController::class, 'viewFile'])->name('legal.viewFile');
});