<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin', 'viewer'])) {
            return redirect()->route('tugas.index');
        }
        
        $data = $this->getDashboardData($request);
        return view('dashboard', $data);
    }

    public function getData(Request $request)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin', 'viewer'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $data = $this->getDashboardData($request);
        return response()->json($data);
    }

    private function getDashboardData(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        
        // Month and Year filtering
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        $dateObj = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $dateObj->copy()->startOfMonth();
        $endOfMonth = $dateObj->copy()->endOfMonth();

        $totalPegawai = Pegawai::count();
        $pegawaiAktif = Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();
        
        $pegawaiPensiun = $totalPegawai - $pegawaiAktif;

        $totalTugas = (int) DB::table('postings')
            ->whereBetween('tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->selectRaw('
            SUM(CASE WHEN link_instagram IS NOT NULL AND link_instagram != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_facebook IS NOT NULL AND link_facebook != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_twitter IS NOT NULL AND link_twitter != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_tiktok IS NOT NULL AND link_tiktok != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_youtube IS NOT NULL AND link_youtube != "" THEN 1 ELSE 0 END)
        ')->value('total');

        $totalTugasTahunIni = (int) DB::table('postings')
            ->whereYear('tanggal_tugas', $year)
            ->selectRaw('
            SUM(CASE WHEN link_instagram IS NOT NULL AND link_instagram != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_facebook IS NOT NULL AND link_facebook != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_twitter IS NOT NULL AND link_twitter != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_tiktok IS NOT NULL AND link_tiktok != "" THEN 1 ELSE 0 END) +
            SUM(CASE WHEN link_youtube IS NOT NULL AND link_youtube != "" THEN 1 ELSE 0 END)
            as total
        ')->value('total');

        // Menghitung total seluruh tugas/link yang berhasil diselesaikan oleh SEMUA pegawai
        // Khusus difilter hanya yang murni diselesaikan oleh pegawai (bukan oleh admin)
        $totalPengerjaanSelesaiSeluruhPegawai = (int) DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->where('absensi_postings.status_selesai', true)
            ->where(function($query) {
                $query->where('absensi_postings.diselesaikan_oleh_admin', false)
                      ->orWhereNull('absensi_postings.diselesaikan_oleh_admin');
            })
            ->whereBetween('postings.tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->selectRaw('
                SUM(CASE WHEN postings.link_instagram IS NOT NULL AND postings.link_instagram != "" THEN 1 ELSE 0 END) +
                SUM(CASE WHEN postings.link_facebook IS NOT NULL AND postings.link_facebook != "" THEN 1 ELSE 0 END) +
                SUM(CASE WHEN postings.link_twitter IS NOT NULL AND postings.link_twitter != "" THEN 1 ELSE 0 END) +
                SUM(CASE WHEN postings.link_tiktok IS NOT NULL AND postings.link_tiktok != "" THEN 1 ELSE 0 END) +
                SUM(CASE WHEN postings.link_youtube IS NOT NULL AND postings.link_youtube != "" THEN 1 ELSE 0 END)
            ')->value('total');

        $rataRataPengerjaanSelesai = 0;
        if ($pegawaiAktif > 0) {
            $rataRataPengerjaanSelesai = round($totalPengerjaanSelesaiSeluruhPegawai / $pegawaiAktif);
        }

        $persentaseKeaktifan = 0;
        if ($totalTugas > 0) {
            $persentaseKeaktifan = round(($rataRataPengerjaanSelesai / $totalTugas) * 100, 1);
        }

        // Kita oper variabel rata-rata ini ke view agar sesuai dengan angka 179
        $totalPengerjaanSelesaiBulanIni = $rataRataPengerjaanSelesai;
        $totalTargetPekerjaan = $totalTugas;

        // Leaderboard Top 5 Bulan Ini
        $topPegawais = DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->join('pegawais', 'absensi_postings.pegawai_id', '=', 'pegawais.id')
            ->where('absensi_postings.status_selesai', true)
            ->where('absensi_postings.diselesaikan_oleh_admin', false)
            ->whereBetween('postings.tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->select(
                'pegawais.nama_pegawai',
                DB::raw('(SUM(ig_like) + SUM(ig_comment) + SUM(ig_share) + SUM(fb_like) + SUM(fb_comment) + SUM(fb_share) + SUM(tw_like) + SUM(tw_comment) + SUM(tw_share) + SUM(tt_like) + SUM(tt_comment) + SUM(tt_share) + SUM(yt_like) + SUM(yt_comment) + SUM(yt_share)) as total_lcs'),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, postings.created_at, absensi_postings.waktu_dikerjakan)) as avg_duration')
            )
            ->groupBy('pegawais.id', 'pegawais.nama_pegawai')
            ->orderByDesc('total_lcs')
            ->orderBy('avg_duration', 'asc')
            ->orderBy('pegawais.nama_pegawai', 'asc')
            ->limit(6)
            ->get();

        // Tren Partisipasi: Bulan Ini (seluruh hari di bulan terpilih)
        $daysInMonth = $dateObj->daysInMonth;
        $trendDates = collect();
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $trendDates->push($dateObj->copy()->day($i)->format('Y-m-d'));
        }

        $trendDataQuery = DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->select(DB::raw('DATE(absensi_postings.waktu_dikerjakan) as date'), DB::raw('COUNT(*) as total'))
            ->where('absensi_postings.status_selesai', true)
            ->where('absensi_postings.diselesaikan_oleh_admin', false)
            ->whereBetween('postings.tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartTrendLabels = $trendDates->map(fn($date) => Carbon::parse($date)->format('d'));
        $chartTrendData = $trendDates->map(fn($date) => $trendDataQuery[$date] ?? 0);

        // Platform Terpopuler Bulan Ini
        $platformStats = DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->where('absensi_postings.status_selesai', true)
            ->where('absensi_postings.diselesaikan_oleh_admin', false)
            ->whereBetween('postings.tanggal_tugas', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('SUM(ig_like + ig_comment + ig_share) as ig'),
                DB::raw('SUM(fb_like + fb_comment + fb_share) as fb'),
                DB::raw('SUM(tw_like + tw_comment + tw_share) as tw'),
                DB::raw('SUM(tt_like + tt_comment + tt_share) as tt'),
                DB::raw('SUM(yt_like + yt_comment + yt_share) as yt')
            )->first();

        $chartPlatformData = [
            $platformStats->ig ?? 0,
            $platformStats->fb ?? 0,
            $platformStats->tw ?? 0,
            $platformStats->tt ?? 0,
            $platformStats->yt ?? 0,
        ];

        return [
            'totalPegawai' => $totalPegawai,
            'pegawaiAktif' => $pegawaiAktif,
            'pegawaiPensiun' => $pegawaiPensiun,
            'totalTugas' => $totalTugas,
            'totalTugasTahunIni' => $totalTugasTahunIni,
            'totalPengerjaanSelesai' => $totalPengerjaanSelesaiBulanIni,
            'totalTargetPekerjaan' => $totalTargetPekerjaan,
            'persentaseKeaktifan' => $persentaseKeaktifan,
            'topPegawais' => $topPegawais,
            'chartTrendLabels' => $chartTrendLabels,
            'chartTrendData' => $chartTrendData,
            'chartPlatformData' => $chartPlatformData,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'monthName' => $dateObj->copy()->locale('id')->translatedFormat('F'),
        ];
    }
}
