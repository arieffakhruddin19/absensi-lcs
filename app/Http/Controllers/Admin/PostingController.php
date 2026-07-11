<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Posting;

class PostingController extends Controller
{
    public function index()
    {
        $postings = Posting::latest()->paginate(10);
        return view('admin.posting.index', compact('postings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_tugas' => 'required|string',
            'tanggal_tugas' => 'nullable|date',
            'batas_waktu' => 'nullable|date',
            'link_instagram' => 'nullable|url',
            'link_facebook' => 'nullable|url',
            'link_twitter' => 'nullable|url',
            'link_tiktok' => 'nullable|url',
            'link_youtube' => 'nullable|url',
        ]);

        Posting::create($request->only(
            'judul_tugas', 'tanggal_tugas', 'batas_waktu', 
            'link_instagram', 'link_facebook', 'link_twitter', 'link_tiktok', 'link_youtube'
        ));

        return redirect()->route('admin.posting.index')->with('success', 'Tugas postingan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $posting = Posting::findOrFail($id);
        return view('admin.posting.edit', compact('posting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_tugas' => 'required|string',
            'tanggal_tugas' => 'nullable|date',
            'batas_waktu' => 'nullable|date',
            'link_instagram' => 'nullable|url',
            'link_facebook' => 'nullable|url',
            'link_twitter' => 'nullable|url',
            'link_tiktok' => 'nullable|url',
            'link_youtube' => 'nullable|url',
        ]);

        $posting = Posting::findOrFail($id);
        $posting->update($request->only(
            'judul_tugas', 'tanggal_tugas', 'batas_waktu', 
            'link_instagram', 'link_facebook', 'link_twitter', 'link_tiktok', 'link_youtube'
        ));

        return redirect()->route('admin.posting.index')->with('success', 'Tugas postingan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Posting::findOrFail($id)->delete();
        return redirect()->route('admin.posting.index')->with('success', 'Postingan berhasil dihapus.');
    }

    public function laporan($id)
    {
        $posting = Posting::findOrFail($id);
        $pegawais = \App\Models\Pegawai::orderBy('nama_pegawai', 'asc')->get();
        
        $absensi = \App\Models\AbsensiPosting::where('posting_id', $id)
                        ->pluck('status_selesai', 'pegawai_id')
                        ->toArray();
                        
        $waktuSelesai = \App\Models\AbsensiPosting::where('posting_id', $id)
                        ->pluck('waktu_dikerjakan', 'pegawai_id')
                        ->toArray();

        return view('admin.posting.laporan', compact('posting', 'pegawais', 'absensi', 'waktuSelesai'));
    }
}
