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

Route::get('/dashboard', function () {
    if (in_array(auth()->user()->role, ['superadmin', 'admin'])) {
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        
        $totalPegawai = \App\Models\Pegawai::count();
        $pegawaiAktif = \App\Models\Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();
        $pegawaiPensiun = $totalPegawai - $pegawaiAktif;
        
        $totalTugas = \App\Models\Posting::count();

        return view('dashboard', compact('totalPegawai', 'pegawaiAktif', 'pegawaiPensiun', 'totalTugas'));
    } else {
        return redirect()->route('tugas.index');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::resource('admin/posting', PostingController::class)->names('admin.posting');
    Route::get('admin/posting/{posting}/laporan', [PostingController::class, 'laporan'])->name('admin.posting.laporan');
    Route::get('admin/posting/{posting}/list-pegawai', [PostingController::class, 'listPegawai'])->name('admin.posting.list-pegawai');
    Route::get('admin/rekap-laporan', [RekapLaporanController::class, 'index'])->name('admin.rekap-laporan');
    Route::get('admin/rekap-laporan/export', [RekapLaporanController::class, 'export'])->name('admin.rekap-laporan.export');
    Route::get('admin/partisipasi-lcs', [TugasController::class, 'partisipasi'])->name('tugas.partisipasi');
    Route::get('admin/partisipasi-lcs/export', [TugasController::class, 'exportPartisipasi'])->name('tugas.partisipasi.export');
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
