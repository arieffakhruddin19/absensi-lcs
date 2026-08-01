<?php
$a = App\Models\AbsensiPosting::where('pegawai_id', 96)->where('posting_id', 1)->first();
echo "Pegawai 96: \n";
echo "ig_like: " . $a->ig_like . "\n";
echo "ig_comment: " . $a->ig_comment . "\n";
echo "ig_share: " . $a->ig_share . "\n";
