<?php
$conn = new mysqli("localhost", "root", "", "pusvetma_lcs");
$sql = "
SELECT 
    po.id as posting_id,
    p.nama_pegawai, 
    po.judul_tugas, 
    po.created_at as dibuat_admin,
    a.waktu_dikerjakan as diklik_selesai
FROM absensi_postings a
JOIN pegawais p ON a.pegawai_id = p.id
JOIN postings po ON a.posting_id = po.id
WHERE p.nama_pegawai = 'drh. Desi Lailatul Hidayah' 
AND a.status_selesai = 1 AND a.diselesaikan_oleh_admin = 0
AND po.tanggal_tugas = '2026-07-20'
ORDER BY po.created_at ASC;
";
$result = $conn->query($sql);

echo "# Fakta Data: Kenapa Waktunya Berbeda?\n\n";
echo "Perhatikan baik-baik tabel di bawah ini. Kolom **ID Tugas** menunjukkan bahwa ini adalah **kotak/card yang berbeda**, meskipun judulnya kembar.\n\n";
echo "| ID Tugas (Card) | Judul Tugas | Dibuat Admin (Submit 1x) | Jam Pegawai Klik Selesai |\n";
echo "|---|---|---|---|\n";

while($row = $result->fetch_assoc()) {
    echo "| **Card #{$row["posting_id"]}** | {$row["judul_tugas"]} | {$row["dibuat_admin"]} | {$row["diklik_selesai"]} |\n";
}
