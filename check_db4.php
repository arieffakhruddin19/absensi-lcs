<?php
$p = App\Models\Posting::where('judul_tugas', 'tes 66')->first();
$all = App\Models\AbsensiPosting::where('posting_id', $p->id)->get();
foreach($all as $a) {
    echo "Pegawai " . $a->pegawai_id . " - L:" . $a->ig_like . " C:" . $a->ig_comment . " S:" . $a->ig_share . "\n";
}
