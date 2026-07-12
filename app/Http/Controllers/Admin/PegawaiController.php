<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Events\AdminDataUpdated;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::orderByRaw('LENGTH(nip) DESC')->orderBy('id', 'asc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhere('nama_pegawai', 'like', "%{$search}%");
            });
        }

        $status = $request->has('status') ? $request->status : 'aktif';
        $today = \Carbon\Carbon::now()->format('Y-m-d');

        if ($status === 'aktif') {
            $query->where(function($q) use ($today) {
                $q->where('tanggal_pensiun', '>=', $today)
                  ->orWhereNull('tanggal_pensiun');
            });
        } elseif ($status === 'pensiun') {
            $query->where('tanggal_pensiun', '<', $today);
        }

        $pegawais = $query->paginate(10)->appends($request->all());

        return view('admin.pegawai.index', compact('pegawais', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:pegawais,nip',
            'nama_pegawai' => 'required|string',
            'tmt' => 'nullable|date',
            'tanggal_pensiun' => 'nullable|date'
        ]);

        $pegawai = Pegawai::create($request->only('nip', 'nama_pegawai', 'tmt', 'tanggal_pensiun'));

        // Generate User automatically
        User::create([
            'name' => $pegawai->nama_pegawai,
            'nip' => $pegawai->nip,
            'password' => Hash::make('12345678'),
            'role' => 'pegawai',
            'pegawai_id' => $pegawai->id,
        ]);

        event(new AdminDataUpdated('pegawai'));

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan dan akun login otomatis dibuat!');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $user = User::where('pegawai_id', $pegawai->id)->first();
        if ($user) {
            $user->delete();
        }
        $pegawai->delete();
        
        event(new AdminDataUpdated('pegawai'));

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai dan akun berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required|unique:pegawais,nip,' . $id,
            'nama_pegawai' => 'required|string',
            'tmt' => 'nullable|date',
            'tanggal_pensiun' => 'nullable|date'
        ]);

        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update($request->only('nip', 'nama_pegawai', 'tmt', 'tanggal_pensiun'));

        // Update User info automatically
        $user = User::where('pegawai_id', $pegawai->id)->first();
        if ($user) {
            $user->update([
                'name' => $pegawai->nama_pegawai,
                'nip' => $pegawai->nip
            ]);
        }

        event(new AdminDataUpdated('pegawai'));

        return redirect()->route('admin.pegawai.index')->with('success', 'Data Pegawai berhasil diperbarui!');
    }

    public function resetPassword($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $user = User::where('pegawai_id', $pegawai->id)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make('12345678')
            ]);
            return redirect()->route('admin.pegawai.index')->with('success', "Password untuk {$pegawai->nama_pegawai} berhasil direset ke 12345678.");
        }
        return redirect()->route('admin.pegawai.index')->with('error', "Gagal mereset password: Akun login tidak ditemukan.");
    }
}
