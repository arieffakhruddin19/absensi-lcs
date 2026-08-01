<?php
$posting = App\Models\Posting::where('judul_tugas', 'tes 66')->first();
$count = App\Models\AbsensiPosting::where('posting_id', $posting->id)->where('ig_like', 1)->count();
echo "Total ig_like for tes 66: " . $count . "\n";
$all = App\Models\AbsensiPosting::where('posting_id', $posting->id)->get();
foreach($all as $a) {
    echo "Pegawai " . $a->pegawai_id . " - ig_like: " . $a->ig_like . " - diselesaikan_oleh_admin: " . $a->diselesaikan_oleh_admin . "\n";
}
