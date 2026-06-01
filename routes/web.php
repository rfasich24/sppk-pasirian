<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\Admin\SekolahCrudController;
use App\Http\Controllers\Admin\AuditTrailController;

// Pengalihan halaman beranda utama langsung ke menu validasi
Route::get('/', [AhpController::class, 'halamanValidasi'])->name('validasi.data');

// Routes Kelompok AHP
Route::get('/matriks-saaty', [AhpController::class, 'halamanMatriks'])->name('ahp.matriks');
Route::post('/simpan-bobot-ahp', [AhpController::class, 'simpanBobotGlobal'])->name('ahp.simpan-bobot');

// Routes Kelompok SMART (Menerima GET dan POST sekaligus)
Route::match(['get', 'post'], '/rekomendasi', [RekomendasiController::class, 'halamanRekomendasi'])->name('smart.rekomendasi');

// Rute Administrasi Kelola Sekolah (CRUD)
Route::get('/admin/sekolah', [SekolahCrudController::class, 'index'])->name('admin.sekolah.index');
Route::post('/admin/sekolah', [SekolahCrudController::class, 'store'])->name('admin.sekolah.store');
Route::put('/admin/sekolah/{id}', [SekolahCrudController::class, 'update'])->name('admin.sekolah.update');
Route::delete('/admin/sekolah/{id}', [SekolahCrudController::class, 'destroy'])->name('admin.sekolah.destroy');

// Rute Panel Pengawasan Ilmiah (Audit Trail Admin)
Route::get('/admin/audit/ahp', [AuditTrailController::class, 'ahpAudit'])->name('admin.audit.ahp');
Route::get('/admin/audit/smart', [AuditTrailController::class, 'smartAudit'])->name('admin.audit.smart');
