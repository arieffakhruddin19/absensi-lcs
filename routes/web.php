<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PostingController;
use App\Http\Controllers\TugasController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin Routes
    Route::resource('admin/pegawai', PegawaiController::class)->names('admin.pegawai');
    Route::resource('admin/posting', PostingController::class)->names('admin.posting');
    Route::get('admin/posting/{posting}/laporan', [PostingController::class, 'laporan'])->name('admin.posting.laporan');
    
    // Pegawai Routes
    Route::get('tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/riwayat', [TugasController::class, 'riwayat'])->name('tugas.riwayat');
    Route::post('tugas/{id}/selesai', [TugasController::class, 'selesaikan'])->name('tugas.selesai');
});

require __DIR__.'/auth.php';
