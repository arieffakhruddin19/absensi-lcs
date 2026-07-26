<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF - Partisipasi LCS</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 16px;
        }
        h3 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 12px;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 0.5pt solid #666;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-left {
            text-align: left;
        }
        .bg-ig { background-color: #fdf2f8; } /* pink-50 */
        .bg-fb { background-color: #eff6ff; } /* blue-50 */
        .bg-tw { background-color: #faf5ff; } /* purple-50 */
        .bg-tt { background-color: #f3f4f6; } /* gray-100 */
        .bg-yt { background-color: #fef2f2; } /* red-50 */
        .bg-total { background-color: #fefce8; font-weight: bold; } /* yellow-50 */
    </style>
</head>
<body>
    <h2>PARTISIPASI PEGAWAI DALAM LCS MEDSOS KEMENTAN, DITJEN PKH DAN PUSVETMA</h2>
    <h3>{{ $subtitle }}</h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">NO.</th>
                <th rowspan="2" style="width: 23%;">NAMA PEGAWAI</th>
                <th colspan="3">INSTAGRAM</th>
                <th colspan="3">FACEBOOK</th>
                <th colspan="3">TWITTER</th>
                <th colspan="3">TIKTOK</th>
                <th colspan="3">YOUTUBE</th>
                <th style="width: 5%; border-bottom: none; vertical-align: bottom; padding-bottom: 0;">TOTAL</th>
                <th style="width: 9%; border-bottom: none; vertical-align: bottom; padding-bottom: 0;">AVG</th>
            </tr>
            <tr>
                <!-- IG -->
                <th style="width: 4%;">L</th>
                <th style="width: 4%;">C</th>
                <th style="width: 4%;">S</th>
                <!-- FB -->
                <th style="width: 4%;">L</th>
                <th style="width: 4%;">C</th>
                <th style="width: 4%;">S</th>
                <!-- TW -->
                <th style="width: 4%;">L</th>
                <th style="width: 4%;">C</th>
                <th style="width: 4%;">S</th>
                <!-- TT -->
                <th style="width: 4%;">L</th>
                <th style="width: 4%;">C</th>
                <th style="width: 4%;">S</th>
                <!-- YT -->
                <th style="width: 4%;">L</th>
                <th style="width: 4%;">C</th>
                <th style="width: 4%;">S</th>
                
                <th style="border-top: none; vertical-align: top; padding-top: 0;">LCS</th>
                <th style="border-top: none; vertical-align: top; padding-top: 0;">SELESAI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pegawais as $index => $pegawai)
                <tr>
                    <td>{{ $pegawai->row_number ?? ($index + 1) }}</td>
                    <td class="text-left">{{ $pegawai->nama_pegawai }}</td>
                    
                    <td>{{ $pegawai->ig_l ?: '-' }}</td>
                    <td>{{ $pegawai->ig_c ?: '-' }}</td>
                    <td>{{ $pegawai->ig_s ?: '-' }}</td>
                    
                    <td>{{ $pegawai->fb_l ?: '-' }}</td>
                    <td>{{ $pegawai->fb_c ?: '-' }}</td>
                    <td>{{ $pegawai->fb_s ?: '-' }}</td>
                    
                    <td>{{ $pegawai->tw_l ?: '-' }}</td>
                    <td>{{ $pegawai->tw_c ?: '-' }}</td>
                    <td>{{ $pegawai->tw_s ?: '-' }}</td>
                    
                    <td>{{ $pegawai->tt_l ?: '-' }}</td>
                    <td>{{ $pegawai->tt_c ?: '-' }}</td>
                    <td>{{ $pegawai->tt_s ?: '-' }}</td>
                    
                    <td>{{ $pegawai->yt_l ?: '-' }}</td>
                    <td>{{ $pegawai->yt_c ?: '-' }}</td>
                    <td>{{ $pegawai->yt_s ?: '-' }}</td>
                    
                    <td style="font-weight: bold;">{{ $pegawai->total_lcs ?? 0 }}</td>
                    <td style="font-weight: bold;">
                        @if(isset($pegawai->avg_duration) && $pegawai->avg_duration < 999999999)
                            @php
                                $seconds = (int)$pegawai->avg_duration;
                                $d = floor($seconds / 86400);
                                $h = floor(($seconds % 86400) / 3600);
                                $m = floor(($seconds % 3600) / 60);
                                $s = $seconds % 60;
                                
                                $timeStr = sprintf('%02d:%02d:%02d', $h, $m, $s);
                                if ($d > 0) {
                                    echo "{$d} hari {$timeStr}";
                                } else {
                                    echo $timeStr;
                                }
                            @endphp
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="19" style="padding: 20px; color: #777;">
                        Belum ada data partisipasi pegawai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
