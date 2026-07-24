<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posting;
use App\Models\AbsensiPosting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\PegawaiDataUpdated;

class TugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cek apakah user adalah pegawai (punya pegawai_id)
        if (!$user->pegawai_id) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak tertaut dengan data pegawai.');
        }

        $completedPostingIds = AbsensiPosting::where('pegawai_id', $user->pegawai_id)
            ->where('status_selesai', true)
            ->pluck('posting_id')
            ->toArray();

        // Ambil semua postingan yang tidak punya deadline ATAU deadline-nya belum lewat
        // DAN belum diselesaikan
        $postings = Posting::where(function($query) {
                $query->whereNull('batas_waktu')
                      ->orWhere('batas_waktu', '>=', Carbon::today());
            })
            ->whereNotIn('id', $completedPostingIds)
            ->latest()
            ->get();

        // Ambil absensi yang status_selesai = false untuk mengambil progress medsos
        $absensiRecords = AbsensiPosting::where('pegawai_id', $user->pegawai_id)
            ->where('status_selesai', false)
            ->get()
            ->keyBy('posting_id');

        return view('pegawai.tugas.index', compact('postings', 'absensiRecords'));
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->pegawai_id) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak tertaut dengan data pegawai.');
        }

        $completedPostingIds = AbsensiPosting::where('pegawai_id', $user->pegawai_id)
            ->where('status_selesai', true)
            ->pluck('posting_id')
            ->toArray();

        // Ambil hanya yang sudah diselesaikan
        $query = Posting::whereIn('id', $completedPostingIds);
        
        if ($request->has('search') && $request->search != '') {
            $query->where('judul_tugas', 'like', '%' . $request->search . '%');
        }
        
        $postings = $query->latest()->paginate(12);

        $absensiRecords = AbsensiPosting::where('pegawai_id', $user->pegawai_id)
            ->where('status_selesai', true)
            ->get()
            ->keyBy('posting_id');

        return view('pegawai.tugas.riwayat', compact('postings', 'absensiRecords'));
    }

    public function tandaiMedsos(Request $request, $posting_id)
    {
        $user = Auth::user();
        if (!$user->pegawai_id) {
            return response()->json(['success' => false, 'message' => 'Bukan akun pegawai']);
        }

        $platform = $request->input('platform'); // 'ig', 'fb', 'tw', 'tt', 'yt'
        $action = $request->input('action'); // 'like', 'comment', 'share'
        $isChecked = $request->input('is_checked', true); // boolean, default true if missing
        
        $validPlatforms = ['ig', 'fb', 'tw', 'tt', 'yt'];
        $validActions = ['like', 'comment', 'share'];

        if (!in_array($platform, $validPlatforms) || !in_array($action, $validActions)) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid']);
        }

        $posting = Posting::findOrFail($posting_id);

        $absensi = AbsensiPosting::firstOrCreate(
            [
                'pegawai_id' => $user->pegawai_id,
                'posting_id' => $posting->id,
            ]
        );

        $field = $platform . '_' . $action;
        $absensi->$field = $isChecked;
        
        // Cek apakah ketiganya sudah true, jika iya catat waktunya (hanya 1x)
        $likeField = $platform . '_like';
        $commentField = $platform . '_comment';
        $shareField = $platform . '_share';
        
        $waktuPlatformMap = [
            'ig' => 'instagram',
            'fb' => 'facebook',
            'tw' => 'twitter',
            'tt' => 'tiktok',
            'yt' => 'youtube'
        ];
        $waktuField = 'waktu_' . $waktuPlatformMap[$platform];

        if ($absensi->$likeField && $absensi->$commentField && $absensi->$shareField && !$absensi->$waktuField) {
            $absensi->$waktuField = Carbon::now();
        } elseif (!$absensi->$likeField || !$absensi->$commentField || !$absensi->$shareField) {
            $absensi->$waktuField = null;
        }

        $absensi->save();

        // Optional: bisa trigger event jika ingin report admin langsung update per klik
        event(new \App\Events\AdminDataUpdated('laporan'));

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menandai ' . strtoupper($action) . ' pada ' . strtoupper($platform) . '.'
        ]);
    }

    public function selesaikan(Request $request, $posting_id)
    {
        $user = Auth::user();
        
        if (!$user->pegawai_id) {
            return response()->json(['success' => false, 'message' => 'Bukan akun pegawai']);
        }

        $posting = Posting::findOrFail($posting_id);

        // Update atau Create data absensi
        $absensi = AbsensiPosting::updateOrCreate(
            [
                'pegawai_id' => $user->pegawai_id,
                'posting_id' => $posting->id,
            ],
            [
                'status_selesai' => true,
                'waktu_dikerjakan' => Carbon::now(),
            ]
        );

        $absensi->save();

        event(new PegawaiDataUpdated('tugas', $user->pegawai_id));
        event(new \App\Events\AdminDataUpdated('laporan'));
        event(new \App\Events\AdminDataUpdated('posting'));
        event(new \App\Events\AdminDataUpdated('rekap'));

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menandai tugas selesai.'
        ]);
    }
}
