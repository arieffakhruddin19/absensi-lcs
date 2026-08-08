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
        $tab = $request->input('tab', 'pkh');
        
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
            'tab' => $request->input('tab', 'pkh')
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

        $tab = $request->input('tab', 'pkh');
        $sumberText = 'Kementan';
        if ($tab == 'pkh') $sumberText = 'Ditjen_PKH';
        elseif ($tab == 'pusvetma') $sumberText = 'Pusvetma';

        return Excel::download(new RekapLaporanExport($rekap, $totalPegawaiAktif, $sumberText), 'Rekap_Laporan_LCS_' . $sumberText . '_' . date('Y-m-d') . '.xlsx');
    }

    public function exportWord(Request $request)
    {
        $rekap = $this->getRekapData($request);
        
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $totalPegawaiAktif = \App\Models\Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();
        $totalPegawaiAktif = $totalPegawaiAktif > 0 ? $totalPegawaiAktif : 1;

        $tab = $request->input('tab', 'pkh');
        $sumberText = 'Kementan';
        if ($tab == 'pkh') $sumberText = 'Ditjen PKH';
        elseif ($tab == 'pusvetma') $sumberText = 'Pusvetma';

        $tanggalStr = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
        if ($request->has('tanggal') && $request->tanggal != '') {
            $tanggalStr = \Carbon\Carbon::parse($request->tanggal)->locale('id')->translatedFormat('l, d F Y');
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);
        $phpWord->setDefaultParagraphStyle(
            array('spaceAfter' => 0, 'spaceBefore' => 0)
        );
        $section = $phpWord->addSection();

        $section->addText('Selamat Pagi Bapak/Ibu', array('bold' => true));
        
        $textRun = $section->addTextRun();
        $textRun->addText("Berikut disampaikan Pemberitaan Media Sosial {$sumberText}, ");
        $textRun->addText("{$tanggalStr} oleh BBVF Pusvetma", array('bold' => true));
        $textRun->addText(" dengan jumlah pegawai {$totalPegawaiAktif} orang.");
        
        $section->addTextBreak(1);

        $groupedRekap = $rekap->groupBy('judul')->reverse();

        foreach ($groupedRekap as $judul => $items) {
            if ($judul) {
                $section->addText($judul, array('bold' => true));
            }
            foreach ($items as $item) {
                $section->addText($item->link);
                
                $likeValue = $item->like;
                if (in_array($item->jenis_medsos, ['Instagram', 'Facebook'])) {
                    $likeValue = $totalPegawaiAktif;
                }
                $likePct = str_replace('.', ',', round(($likeValue / $totalPegawaiAktif) * 100, 1));
                $likeStr = "Like = {$likeValue} Orang ({$likePct}%)";
                $section->addText($likeStr);
                
                $commentPct = str_replace('.', ',', round(($item->comment / $totalPegawaiAktif) * 100, 1));
                $commentStr = "Comment = {$item->comment} Orang ({$commentPct}%)";
                $section->addText($commentStr);
                
                $sharePct = str_replace('.', ',', round(($item->share / $totalPegawaiAktif) * 100, 1));
                $shareStr = "Share = {$item->share} Orang ({$sharePct}%)";
                $section->addText($shareStr);
                
                $section->addTextBreak(1);
            }
        }

        $section->addText('Terima kasih');

        $filename = 'Rekap_Laporan_LCS_' . str_replace(' ', '_', $sumberText) . '_' . date('Y-m-d') . '.docx';
        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportWa(Request $request)
    {
        $rekap = $this->getRekapData($request);
        
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $totalPegawaiAktif = \App\Models\Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();
        $totalPegawaiAktif = $totalPegawaiAktif > 0 ? $totalPegawaiAktif : 1;

        $tab = $request->input('tab', 'pkh');
        $sumberText = 'Kementan';
        if ($tab == 'pkh') $sumberText = 'Ditjen PKH';
        elseif ($tab == 'pusvetma') $sumberText = 'Pusvetma';

        $tanggalStr = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
        if ($request->has('tanggal') && $request->tanggal != '') {
            $tanggalStr = \Carbon\Carbon::parse($request->tanggal)->locale('id')->translatedFormat('l, d F Y');
        }

        $text = "*Selamat Pagi Bapak/Ibu*\n";
        $text .= "Berikut disampaikan Pemberitaan Media Sosial {$sumberText}, *{$tanggalStr} oleh BBVF Pusvetma* dengan jumlah pegawai {$totalPegawaiAktif} orang.\n\n";

        $groupedRekap = $rekap->groupBy('judul')->reverse();

        foreach ($groupedRekap as $judul => $items) {
            if ($judul) {
                $text .= "*{$judul}*\n";
            }
            foreach ($items as $item) {
                $text .= "{$item->link}\n";
                
                $likeValue = $item->like;
                if (in_array($item->jenis_medsos, ['Instagram', 'Facebook'])) {
                    $likeValue = $totalPegawaiAktif;
                }
                $likePct = str_replace('.', ',', round(($likeValue / $totalPegawaiAktif) * 100, 1));
                $text .= "Like = {$likeValue} Orang ({$likePct}%)\n";
                
                $commentPct = str_replace('.', ',', round(($item->comment / $totalPegawaiAktif) * 100, 1));
                $text .= "Comment = {$item->comment} Orang ({$commentPct}%)\n";
                
                $sharePct = str_replace('.', ',', round(($item->share / $totalPegawaiAktif) * 100, 1));
                $text .= "Share = {$item->share} Orang ({$sharePct}%)\n\n";
            }
        }

        $text .= "Terima kasih";

        return response()->json(['text' => $text]);
    }
}
