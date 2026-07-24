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
            ->where('diselesaikan_oleh_admin', false)
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

        // Ambil absensi yang status_selesai = false ATAU diselesaikan_oleh_admin = true (Hutang)
        $absensiRecords = AbsensiPosting::where('pegawai_id', $user->pegawai_id)
            ->where(function($q) {
                $q->where('status_selesai', false)
                  ->orWhere('diselesaikan_oleh_admin', true);
            })
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
            ->where('diselesaikan_oleh_admin', false)
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

    public function monitoring(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->pegawai_id || !$user->pegawai->can_monitor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses monitoring.');
        }

        $query = Posting::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('judul_tugas', 'like', '%' . $request->search . '%');
        }
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal_tugas', $request->tanggal);
        }
        if ($request->has('sumber') && $request->sumber != '') {
            $query->where('sumber_posting', $request->sumber);
        }
        $postings = $query->latest()->paginate(12);

        $stats = [];
        $today = Carbon::today()->toDateString();
        $totalAktif = \App\Models\Pegawai::where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        })->count();

        foreach($postings as $post) {
            $selesai = AbsensiPosting::where('posting_id', $post->id)
                ->where('status_selesai', true)
                ->where('diselesaikan_oleh_admin', false)
                ->count();
            $stats[$post->id] = [
                'total' => $totalAktif,
                'selesai' => $selesai,
                'belum' => $totalAktif - $selesai
            ];
        }

        return view('pegawai.tugas.monitoring', compact('postings', 'stats'));
    }

    public function listPegawai(Request $request, $posting_id)
    {
        $user = Auth::user();
        if (!$user->pegawai_id || !$user->pegawai->can_monitor) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $status = $request->input('status', 'sudah');
        $posting = Posting::findOrFail($posting_id);
        
        $today = \Carbon\Carbon::today()->toDateString();
        $query = \App\Models\Pegawai::query()->where(function($q) use ($today) {
            $q->where('tanggal_pensiun', '>=', $today)
              ->orWhereNull('tanggal_pensiun');
        });

        $finishedPegawaiIds = AbsensiPosting::where('posting_id', $posting_id)
            ->where('status_selesai', true)
            ->where('diselesaikan_oleh_admin', false)
            ->pluck('pegawai_id');
            
        if ($status == 'sudah') {
            $query->whereIn('id', $finishedPegawaiIds);
        } else {
            $query->whereNotIn('id', $finishedPegawaiIds);
        }
        
        // Also fetch the time they completed it if they have
        $pegawais = $query->orderBy('nama_pegawai', 'asc')->get(['id', 'nama_pegawai']);
        
        if ($status == 'sudah') {
            $absensiData = AbsensiPosting::where('posting_id', $posting_id)
                ->where('status_selesai', true)
                ->get()->keyBy('pegawai_id');
                
            $pegawais->transform(function($pegawai) use ($absensiData) {
                $absensi = $absensiData->get($pegawai->id);
                $pegawai->waktu_selesai = $absensi && $absensi->waktu_dikerjakan ? Carbon::parse($absensi->waktu_dikerjakan)->locale('id')->translatedFormat('d M Y H:i') : '-';
                return $pegawai;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => $pegawais
        ]);
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
                'diselesaikan_oleh_admin' => false,
            ]
        );

        if ($request->has('final_state')) {
            $finalState = $request->input('final_state');
            $allowedFields = ['ig_like', 'ig_comment', 'ig_share', 'fb_like', 'fb_comment', 'fb_share', 'tw_like', 'tw_comment', 'tw_share', 'tt_like', 'tt_comment', 'tt_share', 'yt_like', 'yt_comment', 'yt_share'];
            foreach ($finalState as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $absensi->$field = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
            }
        }

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
