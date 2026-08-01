<?php
DB::table('absensi_postings')->where('diselesaikan_oleh_admin', true)->update([
    'ig_like' => 1, 'ig_comment' => 1, 'ig_share' => 1,
    'fb_like' => 1, 'fb_comment' => 1, 'fb_share' => 1,
    'tw_like' => 1, 'tw_comment' => 1, 'tw_share' => 1,
    'tt_like' => 1, 'tt_comment' => 1, 'tt_share' => 1,
    'yt_like' => 1, 'yt_comment' => 1, 'yt_share' => 1,
]);
// Cek kalau ada yang `status_selesai = 1` tapi ada yang 0 (misalnya Pegawai yang lupa centang semua)
// Kita buat sekalian semuanya true kalau status_selesai = 1 ?
// Biar sesuai dengan harapan user (kalau udah selesai berarti semuanya L,C,S).
DB::table('absensi_postings')->where('status_selesai', 1)->update([
    'ig_like' => 1, 'ig_comment' => 1, 'ig_share' => 1,
    'fb_like' => 1, 'fb_comment' => 1, 'fb_share' => 1,
    'tw_like' => 1, 'tw_comment' => 1, 'tw_share' => 1,
    'tt_like' => 1, 'tt_comment' => 1, 'tt_share' => 1,
    'yt_like' => 1, 'yt_comment' => 1, 'yt_share' => 1,
]);
echo "Updated!";
