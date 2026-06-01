<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\RekomendasiController;

// Pengalihan halaman beranda utama langsung ke menu validasi
Route::get('/', [AhpController::class, 'halamanValidasi'])->name('validasi.data');

// Routes Kelompok AHP
Route::get('/matriks-saaty', [AhpController::class, 'halamanMatriks'])->name('ahp.matriks');
Route::post('/simpan-bobot-ahp', [AhpController::class, 'simpanBobotGlobal'])->name('ahp.simpan-bobot');

// Routes Kelompok SMART (Menerima GET dan POST sekaligus)
Route::match(['get', 'post'], '/rekomendasi', [RekomendasiController::class, 'halamanRekomendasi'])->name('smart.rekomendasi');
