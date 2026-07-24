<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Posting;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapLaporanExport;

class RekapLaporanController extends Controller
{
    private function getRekapData(Request $request)
    {
        $tab = $request->input('tab', 'kementan');
        
        $query = Posting::query();
        
        if ($tab == 'pkh') {
            $query->where('sumber_posting', 'Ditjen PKH');
        } elseif ($tab == 'pusvetma') {
            $query->where('sumber_posting', 'Pusvetma');
        } else {
            $query->where('sumber_posting', 'Kementan');
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('judul_tugas', 'like', '%' . $request->search . '%');
        }
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal_tugas', $request->tanggal);
        }
        $postings = $query->latest()->get();
        
        $postingIds = $postings->pluck('id')->toArray();

        $sums = collect();
        if (!empty($postingIds)) {
            $sums = DB::table('absensi_postings')
                ->whereIn('posting_id', $postingIds)
                ->where('status_selesai', true)
                ->select(
                    'posting_id',
                    DB::raw('SUM(ig_like) as ig_like'), DB::raw('SUM(ig_comment) as ig_comment'), DB::raw('SUM(ig_share) as ig_share'),
                    DB::raw('SUM(fb_like) as fb_like'), DB::raw('SUM(fb_comment) as fb_comment'), DB::raw('SUM(fb_share) as fb_share'),
                    DB::raw('SUM(tw_like) as tw_like'), DB::raw('SUM(tw_comment) as tw_comment'), DB::raw('SUM(tw_share) as tw_share'),
                    DB::raw('SUM(tt_like) as tt_like'), DB::raw('SUM(tt_comment) as tt_comment'), DB::raw('SUM(tt_share) as tt_share'),
                    DB::raw('SUM(yt_like) as yt_like'), DB::raw('SUM(yt_comment) as yt_comment'), DB::raw('SUM(yt_share) as yt_share')
                )
                ->groupBy('posting_id')
                ->get()
                ->keyBy('posting_id');
        }

        $rekap = collect();
        $no = 1;

        $filterMedsos = $request->get('jenis_medsos');

        foreach ($postings as $posting) {
            $sum = $sums->get($posting->id);

            if ($posting->link_instagram && (!$filterMedsos || $filterMedsos == 'Instagram')) {
                $rekap->push((object)[
                    'no' => $no++,
                    'judul' => $posting->judul_tugas,
                    'link' => $posting->link_instagram,
                    'jenis_medsos' => 'Instagram',
                    'tanggal' => $posting->tanggal_tugas,
                    'sumber' => $posting->sumber_posting,
                    'like' => $sum->ig_like ?? 0,
                    'comment' => $sum->ig_comment ?? 0,
                    'share' => $sum->ig_share ?? 0,
                ]);
            }
            if ($posting->link_facebook && (!$filterMedsos || $filterMedsos == 'Facebook')) {
                $rekap->push((object)[
                    'no' => $no++,
                    'judul' => $posting->judul_tugas,
                    'link' => $posting->link_facebook,
                    'jenis_medsos' => 'Facebook',
                    'tanggal' => $posting->tanggal_tugas,
                    'sumber' => $posting->sumber_posting,
                    'like' => $sum->fb_like ?? 0,
                    'comment' => $sum->fb_comment ?? 0,
                    'share' => $sum->fb_share ?? 0,
                ]);
            }
            if ($posting->link_twitter && (!$filterMedsos || $filterMedsos == 'Twitter')) {
                $rekap->push((object)[
                    'no' => $no++,
                    'judul' => $posting->judul_tugas,
                    'link' => $posting->link_twitter,
                    'jenis_medsos' => 'Twitter',
                    'tanggal' => $posting->tanggal_tugas,
                    'sumber' => $posting->sumber_posting,
                    'like' => $sum->tw_like ?? 0,
                    'comment' => $sum->tw_comment ?? 0,
                    'share' => $sum->tw_share ?? 0,
                ]);
            }
            if ($posting->link_tiktok && (!$filterMedsos || $filterMedsos == 'TikTok')) {
                $rekap->push((object)[
                    'no' => $no++,
                    'judul' => $posting->judul_tugas,
                    'link' => $posting->link_tiktok,
                    'jenis_medsos' => 'TikTok',
                    'tanggal' => $posting->tanggal_tugas,
                    'sumber' => $posting->sumber_posting,
                    'like' => $sum->tt_like ?? 0,
                    'comment' => $sum->tt_comment ?? 0,
                    'share' => $sum->tt_share ?? 0,
                ]);
            }
            if ($posting->link_youtube && (!$filterMedsos || $filterMedsos == 'YouTube')) {
                $rekap->push((object)[
                    'no' => $no++,
                    'judul' => $posting->judul_tugas,
                    'link' => $posting->link_youtube,
                    'jenis_medsos' => 'YouTube',
                    'tanggal' => $posting->tanggal_tugas,
                    'sumber' => $posting->sumber_posting,
                    'like' => $sum->yt_like ?? 0,
                    'comment' => $sum->yt_comment ?? 0,
                    'share' => $sum->yt_share ?? 0,
                ]);
            }
        }

        return $rekap;
    }

    public function index(Request $request)
    {
        $rekap = $this->getRekapData($request);

        $perPage = $request->input('per_page', 10);
        $page = $request->get('page', 1);
        $paginatedRekap = new \Illuminate\Pagination\LengthAwarePaginator(
            $rekap->forPage($page, $perPage)->values(),
            $rekap->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $totalPegawaiAktif = \App\Models\Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();
        
        $totalPegawaiAktif = $totalPegawaiAktif > 0 ? $totalPegawaiAktif : 1;

        return view('admin.rekap-laporan.index', [
            'rekap' => $paginatedRekap, 
            'totalPegawaiAktif' => $totalPegawaiAktif,
            'tab' => $request->input('tab', 'kementan')
        ]);
    }

    public function export(Request $request)
    {
        $rekap = $this->getRekapData($request);
        
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $totalPegawaiAktif = \App\Models\Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();
        $totalPegawaiAktif = $totalPegawaiAktif > 0 ? $totalPegawaiAktif : 1;

        $tab = $request->input('tab', 'kementan');
        $sumberText = 'Kementan';
        if ($tab == 'pkh') $sumberText = 'Ditjen_PKH';
        elseif ($tab == 'pusvetma') $sumberText = 'Pusvetma';

        return Excel::download(new RekapLaporanExport($rekap, $totalPegawaiAktif, $sumberText), 'Rekap_Laporan_LCS_' . $sumberText . '_' . date('Y-m-d') . '.xlsx');
    }
}
