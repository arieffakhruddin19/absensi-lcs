<?php
$absensis = App\Models\AbsensiPosting::where('diselesaikan_oleh_admin', true)->get();
foreach($absensis as $absensi) {
    $posting = $absensi->posting;
    if ($posting) {
        if ($posting->link_instagram) { $absensi->ig_like = true; $absensi->ig_comment = true; $absensi->ig_share = true; }
        if ($posting->link_facebook) { $absensi->fb_like = true; $absensi->fb_comment = true; $absensi->fb_share = true; }
        if ($posting->link_twitter) { $absensi->tw_like = true; $absensi->tw_comment = true; $absensi->tw_share = true; }
        if ($posting->link_tiktok) { $absensi->tt_like = true; $absensi->tt_comment = true; $absensi->tt_share = true; }
        if ($posting->link_youtube) { $absensi->yt_like = true; $absensi->yt_comment = true; $absensi->yt_share = true; }
        $absensi->save();
    }
}
echo 'Fixed ' . count($absensis) . ' records.';
