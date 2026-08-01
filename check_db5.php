<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(App\Models\Posting::where('judul_tugas', 'like', '%Komisi IV%')->get() as $p) {
    echo 'ID: ' . $p->id . ' | Likes: ' . DB::table('absensi_postings')->where('posting_id', $p->id)->where('status_selesai', true)->where('diselesaikan_oleh_admin', false)->sum('tt_like') . PHP_EOL;
}
