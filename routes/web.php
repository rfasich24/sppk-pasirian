<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\RekomendasiController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-ahp', [AhpController::class, 'cekHitunganDua']);
Route::post('/simpan-bobot-ahp', [AhpController::class, 'simpanBobotGlobal'])->name('ahp.simpan-bobot');
Route::get('/test-smart', [RekomendasiController::class, 'testSmartEksekusi']);
