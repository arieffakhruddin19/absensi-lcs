<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pegawais = \App\Models\Pegawai::withCount(['absensiPostings as total_lcs' => function($query) {
    $query->where('status_selesai', true);
}])->orderByDesc('total_lcs')->take(2)->get();
echo json_encode($pegawais->toArray());
