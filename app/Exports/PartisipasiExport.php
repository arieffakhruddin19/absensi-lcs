<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PartisipasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $search;

    public function __construct($startDate, $endDate, $search)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
    }

    public function collection()
    {
        $absensiQuery = DB::table('absensi_postings')
            ->join('postings', 'absensi_postings.posting_id', '=', 'postings.id')
            ->where('absensi_postings.status_selesai', true)
            ->where('absensi_postings.diselesaikan_oleh_admin', false);

        if ($this->startDate) {
            $absensiQuery->whereDate('postings.tanggal_tugas', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $absensiQuery->whereDate('postings.tanggal_tugas', '<=', $this->endDate);
        }

        $sums = $absensiQuery->select(
                'absensi_postings.pegawai_id',
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, postings.created_at, absensi_postings.waktu_dikerjakan)) as avg_duration'),
                DB::raw('SUM(absensi_postings.ig_like) as ig_l, SUM(absensi_postings.ig_comment) as ig_c, SUM(absensi_postings.ig_share) as ig_s'),
                DB::raw('SUM(absensi_postings.fb_like) as fb_l, SUM(absensi_postings.fb_comment) as fb_c, SUM(absensi_postings.fb_share) as fb_s'),
                DB::raw('SUM(absensi_postings.tw_like) as tw_l, SUM(absensi_postings.tw_comment) as tw_c, SUM(absensi_postings.tw_share) as tw_s'),
                DB::raw('SUM(absensi_postings.tt_like) as tt_l, SUM(absensi_postings.tt_comment) as tt_c, SUM(absensi_postings.tt_share) as tt_s'),
                DB::raw('SUM(absensi_postings.yt_like) as yt_l, SUM(absensi_postings.yt_comment) as yt_c, SUM(absensi_postings.yt_share) as yt_s')
            )
            ->groupBy('absensi_postings.pegawai_id')
            ->get()
            ->keyBy('pegawai_id');

        $today = \Carbon\Carbon::today()->toDateString();
        $queryPegawai = Pegawai::where(function($q) use ($today) {
                $q->where('tanggal_pensiun', '>=', $today)
                  ->orWhereNull('tanggal_pensiun');
            });

        if ($this->search) {
            $queryPegawai->where('nama_pegawai', 'like', '%' . $this->search . '%');
        }

        $pegawais = $queryPegawai->get();

        foreach ($pegawais as $pegawai) {
            $sum = $sums->get($pegawai->id);
            $pegawai->avg_duration = $sum->avg_duration ?? 999999999;
            
            $pegawai->ig_l = $sum->ig_l ?? 0;
            $pegawai->ig_c = $sum->ig_c ?? 0;
            $pegawai->ig_s = $sum->ig_s ?? 0;
            
            $pegawai->fb_l = $sum->fb_l ?? 0;
            $pegawai->fb_c = $sum->fb_c ?? 0;
            $pegawai->fb_s = $sum->fb_s ?? 0;
            
            $pegawai->tw_l = $sum->tw_l ?? 0;
            $pegawai->tw_c = $sum->tw_c ?? 0;
            $pegawai->tw_s = $sum->tw_s ?? 0;
            
            $pegawai->tt_l = $sum->tt_l ?? 0;
            $pegawai->tt_c = $sum->tt_c ?? 0;
            $pegawai->tt_s = $sum->tt_s ?? 0;
            
            $pegawai->yt_l = $sum->yt_l ?? 0;
            $pegawai->yt_c = $sum->yt_c ?? 0;
            $pegawai->yt_s = $sum->yt_s ?? 0;

            $pegawai->total_lcs = 
                $pegawai->ig_l + $pegawai->ig_c + $pegawai->ig_s +
                $pegawai->fb_l + $pegawai->fb_c + $pegawai->fb_s +
                $pegawai->tw_l + $pegawai->tw_c + $pegawai->tw_s +
                $pegawai->tt_l + $pegawai->tt_c + $pegawai->tt_s +
                $pegawai->yt_l + $pegawai->yt_c + $pegawai->yt_s;
        }

        $pegawais = $pegawais->sortBy([
            ['total_lcs', 'desc'],
            ['avg_duration', 'asc'],
            ['nama_pegawai', 'asc'],
        ])->values();

        // Add a row number
        $iteration = 1;
        foreach ($pegawais as $pegawai) {
            $pegawai->row_number = $iteration++;
        }

        return $pegawais;
    }

    public function headings(): array
    {
        return [
            ['NO.', 'NAMA PEGAWAI', 'IG', '', '', 'FB', '', '', 'TW', '', '', 'TT', '', '', 'YT', '', '', 'TOTAL LCS'],
            ['', '', 'L', 'C', 'S', 'L', 'C', 'S', 'L', 'C', 'S', 'L', 'C', 'S', 'L', 'C', 'S', '']
        ];
    }

    public function map($row): array
    {
        return [
            $row->row_number,
            $row->nama_pegawai,
            $row->ig_l, $row->ig_c, $row->ig_s,
            $row->fb_l, $row->fb_c, $row->fb_s,
            $row->tw_l, $row->tw_c, $row->tw_s,
            $row->tt_l, $row->tt_c, $row->tt_s,
            $row->yt_l, $row->yt_c, $row->yt_s,
            $row->total_lcs
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for headings
        $sheet->mergeCells('A1:A2'); // NO.
        $sheet->mergeCells('B1:B2'); // NAMA PEGAWAI
        $sheet->mergeCells('C1:E1'); // IG
        $sheet->mergeCells('F1:H1'); // FB
        $sheet->mergeCells('I1:K1'); // TW
        $sheet->mergeCells('L1:N1'); // TT
        $sheet->mergeCells('O1:Q1'); // YT
        $sheet->mergeCells('R1:R2'); // TOTAL LCS

        // Style the headings
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]
        ];
    }
}
