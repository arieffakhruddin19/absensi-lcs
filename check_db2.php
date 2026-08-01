<?php
$posting = App\Models\Posting::where('judul_tugas', 'tes 66')->first();
$all = App\Models\AbsensiPosting::where('posting_id', $posting->id)->get();
foreach($all as $a) {
    echo "Pegawai " . $a->pegawai_id . " - ig_like: " . $a->ig_like . " - status_selesai: " . $a->status_selesai . " - diselesaikan_oleh_admin: " . $a->diselesaikan_oleh_admin . "\n";
}
