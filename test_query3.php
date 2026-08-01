<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$subquery = \App\Models\AbsensiPosting::selectRaw('SUM(ig_like + ig_comment + ig_share + fb_like + fb_comment + fb_share + tw_like + tw_comment + tw_share + tt_like + tt_comment + tt_share + yt_like + yt_comment + yt_share)')
    ->whereColumn('pegawai_id', 'pegawais.id')
    ->where('status_selesai', true)
    ->where('diselesaikan_oleh_admin', false);

$pegawais = \App\Models\Pegawai::select('pegawais.*')
    ->selectSub($subquery, 'total_lcs')
    ->orderByDesc('total_lcs')
    ->take(2)
    ->get();

echo json_encode($pegawais->toArray());
