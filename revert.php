<?php
// We only have test data for tes 66 (posting_id = 58)
DB::table('absensi_postings')->where('pegawai_id', 96)->where('posting_id', 58)->update([
    'ig_like' => 0
]);
echo "Reverted Pegawai 96";
