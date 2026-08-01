<?php
$conn = new mysqli("localhost", "root", "", "pusvetma_lcs");
$sql = "
SELECT 
    p.nama_pegawai, 
    po.judul_tugas, 
    po.tanggal_tugas,
    po.created_at as post_dibuat,
    a.waktu_dikerjakan as dikerjakan,
    TIMESTAMPDIFF(SECOND, po.created_at, a.waktu_dikerjakan) as selisih_detik
FROM absensi_postings a
JOIN pegawais p ON a.pegawai_id = p.id
JOIN postings po ON a.posting_id = po.id
WHERE a.status_selesai = 1 AND a.diselesaikan_oleh_admin = 0
AND po.tanggal_tugas >= '2026-07-01' AND po.tanggal_tugas <= '2026-07-26'
AND p.nama_pegawai IN (
    'drh. Desi Lailatul Hidayah', 
    'drh. Ferra Hendrawati', 
    'M. Apri Nurdi', 
    'Dr. drh  Dewi Noor Hidayati, M.Kes.'
)
ORDER BY FIELD(p.nama_pegawai, 'drh. Desi Lailatul Hidayah', 'drh. Ferra Hendrawati', 'M. Apri Nurdi', 'Dr. drh  Dewi Noor Hidayati, M.Kes.'), po.tanggal_tugas ASC, po.id ASC;
";
$result = $conn->query($sql);

function formatDuration($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    if ($hours > 0) return "{$hours} jam {$minutes} mnt";
    return "{$minutes} mnt";
}

$current_employee = "";
echo "# Detail Waktu Penyelesaian Per Postingan\n\n";
while($row = $result->fetch_assoc()) {
    if ($current_employee != $row["nama_pegawai"]) {
        $current_employee = $row["nama_pegawai"];
        echo "\n### " . $current_employee . "\n";
        echo "| Tanggal Tugas | Postingan | Dibuat Admin | Diselesaikan | Durasi (Cepat/Lambat) |\n";
        echo "|---|---|---|---|---|\n";
    }
    $durasi = formatDuration($row["selisih_detik"]);
    echo "| {$row["tanggal_tugas"]} | {$row["judul_tugas"]} | {$row["post_dibuat"]} | {$row["dikerjakan"]} | {$durasi} |\n";
}
