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

        // Query Leaderboard Top 5 Bulan Ini
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth();

        $topPegawais = \Illuminate\Support\Facades\DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->join('pegawais', 'absensi_postings.pegawai_id', '=', 'pegawais.id')
            ->where('absensi_postings.status_selesai', true)
            ->where('absensi_postings.diselesaikan_oleh_admin', false)
            ->whereBetween('postings.tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->select(
                'pegawais.nama_pegawai',
                \Illuminate\Support\Facades\DB::raw('(SUM(ig_like) + SUM(ig_comment) + SUM(ig_share) + SUM(fb_like) + SUM(fb_comment) + SUM(fb_share) + SUM(tw_like) + SUM(tw_comment) + SUM(tw_share) + SUM(tt_like) + SUM(tt_comment) + SUM(tt_share) + SUM(yt_like) + SUM(yt_comment) + SUM(yt_share)) as total_lcs')
            )
            ->groupBy('pegawais.id', 'pegawais.nama_pegawai')
            ->orderByDesc('total_lcs')
            ->limit(5)
            ->get();

        // Query Tren Partisipasi 7 Hari Terakhir
        $trendDates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $trendDates->push(\Carbon\Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        $trendDataQuery = \Illuminate\Support\Facades\DB::table('absensi_postings')
            ->select(\Illuminate\Support\Facades\DB::raw('DATE(waktu_dikerjakan) as date'), \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->where('status_selesai', true)
            ->where('diselesaikan_oleh_admin', false)
            ->whereDate('waktu_dikerjakan', '>=', \Carbon\Carbon::now()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartTrendLabels = $trendDates->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'));
        $chartTrendData = $trendDates->map(fn($date) => $trendDataQuery[$date] ?? 0);

        // Query Platform Terpopuler Bulan Ini
        $platformStats = \Illuminate\Support\Facades\DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->where('absensi_postings.status_selesai', true)
            ->where('absensi_postings.diselesaikan_oleh_admin', false)
            ->whereBetween('postings.tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->select(
                \Illuminate\Support\Facades\DB::raw('SUM(ig_like + ig_comment + ig_share) as ig'),
                \Illuminate\Support\Facades\DB::raw('SUM(fb_like + fb_comment + fb_share) as fb'),
                \Illuminate\Support\Facades\DB::raw('SUM(tw_like + tw_comment + tw_share) as tw'),
                \Illuminate\Support\Facades\DB::raw('SUM(tt_like + tt_comment + tt_share) as tt'),
                \Illuminate\Support\Facades\DB::raw('SUM(yt_like + yt_comment + yt_share) as yt')
            )->first();

        $chartPlatformData = [
            $platformStats->ig ?? 0,
            $platformStats->fb ?? 0,
            $platformStats->tw ?? 0,
            $platformStats->tt ?? 0,
            $platformStats->yt ?? 0,
        ];

        return view('dashboard', compact(
            'totalPegawai', 'pegawaiAktif', 'pegawaiPensiun', 'totalTugas',
            'topPegawais', 'chartTrendLabels', 'chartTrendData', 'chartPlatformData'
        ));
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
