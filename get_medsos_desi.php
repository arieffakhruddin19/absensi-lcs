<?php
$conn = new mysqli("localhost", "root", "", "pusvetma_lcs");
$sql = "SELECT po.judul_tugas, po.created_at, a.waktu_instagram, a.waktu_facebook, a.waktu_twitter, a.waktu_tiktok, a.waktu_youtube, a.waktu_dikerjakan, TIMESTAMPDIFF(SECOND, po.created_at, a.waktu_dikerjakan) as selisih_sistem FROM absensi_postings a JOIN pegawais p ON a.pegawai_id = p.id JOIN postings po ON a.posting_id = po.id WHERE p.nama_pegawai = 'drh. Desi Lailatul Hidayah' AND a.status_selesai = 1 AND a.diselesaikan_oleh_admin = 0 ORDER BY po.created_at ASC LIMIT 15;";
$result = $conn->query($sql);
function formatDur($seconds) { if ($seconds === null) return "-"; if ($seconds < 0) return "0d"; $h = floor($seconds / 3600); $m = floor(($seconds % 3600) / 60); $s = $seconds % 60; $res = []; if ($h > 0) $res[] = "{$h}j"; if ($m > 0) $res[] = "{$m}m"; if ($s > 0 || empty($res)) $res[] = "{$s}d"; return implode(" ", $res); }
function getDiff($start, $end) { if (!$end) return null; return strtotime($end) - strtotime($start); }
$md = "# Rincian Waktu Penyelesaian per Medsos (drh. Desi)\n\nTabel ini menampilkan sampel 15 tugas drh. Desi. Semua waktu dihitung **sejak Admin men-submit tugas** hingga drh. Desi mencentang masing-masing link medsos.\n\n| Judul Tugas | Dibuat Admin | IG | FB | X | TikTok | YouTube | **Klik Selesai** |\n|---|---|---|---|---|---|---|---|\n";
$total_sistem = 0; $count = 0;
while($row = $result->fetch_assoc()) {
    $start = $row["created_at"];
    $ig = formatDur(getDiff($start, $row["waktu_instagram"]));
    $fb = formatDur(getDiff($start, $row["waktu_facebook"]));
    $tw = formatDur(getDiff($start, $row["waktu_twitter"]));
    $tt = formatDur(getDiff($start, $row["waktu_tiktok"]));
    $yt = formatDur(getDiff($start, $row["waktu_youtube"]));
    $sys = formatDur($row["selisih_sistem"]);
    $total_sistem += $row["selisih_sistem"]; $count++;
    $md .= "| {$row["judul_tugas"]} | $start | $ig | $fb | $tw | $tt | $yt | **$sys** |\n";
}
// Get full average
$sql_avg = "SELECT COUNT(*) as c, AVG(TIMESTAMPDIFF(SECOND, po.created_at, a.waktu_dikerjakan)) as a_sys FROM absensi_postings a JOIN pegawais p ON a.pegawai_id = p.id JOIN postings po ON a.posting_id = po.id WHERE p.nama_pegawai = 'drh. Desi Lailatul Hidayah' AND a.status_selesai = 1 AND a.diselesaikan_oleh_admin = 0";
$res_avg = $conn->query($sql_avg)->fetch_assoc();
$full_count = $res_avg["c"]; $full_avg = formatDur($res_avg["a_sys"]);
$md .= "\n## Rata-Rata Sistem Saat Ini (drh. Desi)\nTotal Tugas Dikerjakan: **$full_count Tugas**\nRata-Rata Waktu Penyelesaian Keseluruhan: **$full_avg**\n\n*(Angka rata-rata inilah yang dihitung oleh sistem dan membuat drh. Desi berada di Peringkat 1)*\n";
file_put_contents("C:\\Users\\arief fakhruddin\\.gemini\\antigravity-ide\\brain\\69fdc576-a279-4a4e-84ce-cc8748719465\\rincian_medsos_desi.md", $md);
?>