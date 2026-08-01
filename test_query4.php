<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sums = DB::table('absensi_postings')
    ->where('status_selesai', true)
    ->where('diselesaikan_oleh_admin', false)
    ->select('pegawai_id', DB::raw('SUM(ig_like) as ig_l'), DB::raw('SUM(fb_like) as fb_l'))
    ->groupBy('pegawai_id')
    ->get()
    ->keyBy('pegawai_id');

echo json_encode($sums->take(5));
