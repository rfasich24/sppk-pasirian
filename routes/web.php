<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SekolahCrudController;
use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Middleware\EnsureAdminAuthenticated;

// 1. RUTE PUBLIK / GUEST (Bebas Akses Tanpa Login)
Route::get('/', [RekomendasiController::class, 'halamanRekomendasi'])->name('validasi.data');
Route::match(['get', 'post'], '/rekomendasi', [RekomendasiController::class, 'halamanRekomendasi'])->name('smart.rekomendasi');
Route::match(['get', 'post'], '/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');


// 2. RUTE PROTEKSI ADMIN (Menggunakan Middleware Class Resmi)
Route::middleware(EnsureAdminAuthenticated::class)->group(function () {

    // Rute Ubah Tipe Kriteria (Cost <=> Benefit)
    Route::patch('/admin/kriteria/{id}/toggle-type', [AuditTrailController::class, 'toggleType'])->name('admin.kriteria.toggle-type');

    // Modul CRUD Kelola Sekolah
    Route::get('/admin/sekolah', [SekolahCrudController::class, 'index'])->name('admin.sekolah.index');
    Route::post('/admin/sekolah', [SekolahCrudController::class, 'store'])->name('admin.sekolah.store');
    Route::put('/admin/sekolah/{id}', [SekolahCrudController::class, 'update'])->name('admin.sekolah.update');
    Route::delete('/admin/sekolah/{id}', [SekolahCrudController::class, 'destroy'])->name('admin.sekolah.destroy');

    // Modul Audit Trail Matematika
    Route::get('/admin/audit/ahp', [AuditTrailController::class, 'ahpAudit'])->name('admin.audit.ahp');
    Route::get('/admin/audit/smart', [AuditTrailController::class, 'smartAudit'])->name('admin.audit.smart');

});
