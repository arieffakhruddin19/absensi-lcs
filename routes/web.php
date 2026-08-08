<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PostingController;
use App\Http\Controllers\Admin\RekapLaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TugasController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public Routes
Route::get('rekap-laporan-lcs', [\App\Http\Controllers\PublicRekapController::class, 'index'])->name('public.rekap-laporan');
Route::get('rekap-laporan-lcs/export', [\App\Http\Controllers\PublicRekapController::class, 'export'])->name('public.rekap-laporan.export');

use App\Http\Controllers\Admin\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/api/dashboard/stats', [DashboardController::class, 'getData'])->middleware(['auth', 'verified'])->name('api.dashboard.stats');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (superadmin + admin)
Route::middleware(['auth', 'checkRole:superadmin,admin'])->group(function () {
    Route::resource('admin/pegawai', PegawaiController::class)->names('admin.pegawai');
    Route::post('admin/pegawai/{pegawai}/reset-password', [PegawaiController::class, 'resetPassword'])->name('admin.pegawai.reset-password');
    Route::post('admin/pegawai/{pegawai}/toggle-monitor', [PegawaiController::class, 'toggleMonitor'])->name('admin.pegawai.toggle-monitor');
    Route::resource('admin/posting', PostingController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->names('admin.posting');
});

// Admin + Viewer Routes
Route::middleware(['auth', 'checkRole:superadmin,admin,viewer'])->group(function () {
    Route::resource('admin/posting', PostingController::class)->only(['index', 'show'])->names('admin.posting');
    Route::get('admin/posting/{posting}/laporan', [PostingController::class, 'laporan'])->name('admin.posting.laporan');
    Route::get('admin/posting/{posting}/list-pegawai', [PostingController::class, 'listPegawai'])->name('admin.posting.list-pegawai');
    Route::get('admin/rekap-laporan', [RekapLaporanController::class, 'index'])->name('admin.rekap-laporan');
    Route::get('admin/rekap-laporan/export', [RekapLaporanController::class, 'export'])->name('admin.rekap-laporan.export');
    Route::get('admin/rekap-laporan/export-word', [RekapLaporanController::class, 'exportWord'])->name('admin.rekap-laporan.export-word');
    Route::get('admin/rekap-laporan/export-wa', [RekapLaporanController::class, 'exportWa'])->name('admin.rekap-laporan.export-wa');
    Route::get('admin/partisipasi-lcs', [TugasController::class, 'partisipasi'])->name('tugas.partisipasi');
    Route::get('admin/partisipasi-lcs/export', [TugasController::class, 'exportPartisipasi'])->name('tugas.partisipasi.export');
    Route::get('admin/partisipasi-lcs/export-pdf', [TugasController::class, 'exportPdfPartisipasi'])->name('tugas.partisipasi.pdf');
});

// Superadmin Only Routes
Route::middleware(['auth', 'checkRole:superadmin'])->group(function () {
    // User Management
    Route::resource('admin/user', UserController::class)->names('admin.user');

    // Fitur Isi LCS Pegawai
    Route::post('admin/posting/{posting}/tandai-medsos/{pegawai}', [PostingController::class, 'tandaiMedsosSuperadmin'])->name('admin.posting.tandai-medsos');
    Route::post('admin/posting/{posting}/selesaikan-lcs/{pegawai}', [PostingController::class, 'selesaikanSuperadmin'])->name('admin.posting.selesaikan-lcs');
});

// Pegawai Routes
Route::middleware(['auth', 'checkRole:pegawai'])->group(function () {
    Route::get('tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/riwayat', [TugasController::class, 'riwayat'])->name('tugas.riwayat');
    Route::get('tugas/monitoring', [TugasController::class, 'monitoring'])->name('tugas.monitoring');
    Route::get('tugas/{id}/list-pegawai', [TugasController::class, 'listPegawai'])->name('tugas.list-pegawai');
    Route::post('tugas/{id}/medsos', [TugasController::class, 'tandaiMedsos'])->name('tugas.medsos');
    Route::post('tugas/{id}/selesai', [TugasController::class, 'selesaikan'])->name('tugas.selesai');
});

require __DIR__.'/auth.php';
