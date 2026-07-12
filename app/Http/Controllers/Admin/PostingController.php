<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Posting;
use App\Events\AdminDataUpdated;

class PostingController extends Controller
{
    public function index(Request $request)
    {
        $query = Posting::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('judul_tugas', 'like', '%' . $request->search . '%');
        }
        $postings = $query->latest()->paginate(10);
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

        event(new AdminDataUpdated('posting'));

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

        event(new AdminDataUpdated('posting'));

        return redirect()->route('admin.posting.index')->with('success', 'Tugas postingan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Posting::findOrFail($id)->delete();
        event(new AdminDataUpdated('posting'));
        return redirect()->route('admin.posting.index')->with('success', 'Postingan berhasil dihapus.');
    }

    public function laporan(Request $request, $id)
    {
        $posting = Posting::findOrFail($id);
        
        $query = \App\Models\Pegawai::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_pegawai', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != 'semua') {
            $finishedPegawaiIds = \App\Models\AbsensiPosting::where('posting_id', $id)
                ->where('status_selesai', true)
                ->pluck('pegawai_id');
                
            if ($request->status == 'sudah') {
                $query->whereIn('id', $finishedPegawaiIds);
            } elseif ($request->status == 'belum') {
                $query->whereNotIn('id', $finishedPegawaiIds);
            }
        }
        
        $pegawais = $query->orderByRaw('LENGTH(nip) DESC')->orderBy('id', 'asc')->paginate(15);
        
        $absensiRecords = \App\Models\AbsensiPosting::where('posting_id', $id)
                        ->whereIn('pegawai_id', $pegawais->pluck('id'))
                        ->get()
                        ->keyBy('pegawai_id');

        return view('admin.posting.laporan', compact('posting', 'pegawais', 'absensiRecords'));
    }
}
