<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posting;
use App\Models\AbsensiPosting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $absensi = []; // Kosong karena yang muncul di sini pasti belum selesai

        return view('pegawai.tugas.index', compact('postings', 'absensi'));
    }

    public function riwayat()
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
        $postings = Posting::whereIn('id', $completedPostingIds)
            ->latest()
            ->get();

        // Semua tugas di sini sudah selesai
        $absensi = array_fill_keys($completedPostingIds, 1);

        return view('pegawai.tugas.riwayat', compact('postings', 'absensi'));
    }

    public function selesaikan(Request $request, $posting_id)
    {
        $user = Auth::user();
        
        if (!$user->pegawai_id) {
            return response()->json(['success' => false, 'message' => 'Bukan akun pegawai']);
        }

        $posting = Posting::findOrFail($posting_id);

        // Update atau Create data absensi
        AbsensiPosting::updateOrCreate(
            [
                'pegawai_id' => $user->pegawai_id,
                'posting_id' => $posting->id,
            ],
            [
                'status_selesai' => true,
                'waktu_dikerjakan' => Carbon::now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menandai tugas selesai.'
        ]);
    }
}
