<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapLaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $totalPegawaiAktif;

    public function __construct($data, $totalPegawaiAktif = 1)
    {
        $this->data = $data;
        $this->totalPegawaiAktif = $totalPegawaiAktif > 0 ? $totalPegawaiAktif : 1;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'JUDUL POSTINGAN',
            'LINK',
            'MEDSOS',
            'JUMLAH LIKE',
            'JUMLAH COMMENT',
            'JUMLAH SHARE'
        ];
    }

    public function map($row): array
    {
        return [
            $row->no,
            \Carbon\Carbon::parse($row->tanggal)->locale('id')->translatedFormat('d F Y'),
            $row->judul,
            $row->link,
            $row->jenis_medsos,
            $row->like . ' (' . round(($row->like / $this->totalPegawaiAktif) * 100, 1) . '%)',
            $row->comment . ' (' . round(($row->comment / $this->totalPegawaiAktif) * 100, 1) . '%)',
            $row->share . ' (' . round(($row->share / $this->totalPegawaiAktif) * 100, 1) . '%)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text and center aligned
            1    => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Center align specific columns
            'A'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'E'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'F'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'G'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'H'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }
}
