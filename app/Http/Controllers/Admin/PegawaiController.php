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
    public function index()
    {
        $pegawais = Pegawai::latest()->paginate(10);
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:pegawais,nip',
            'nama_pegawai' => 'required|string',
            'divisi' => 'nullable|string'
        ]);

        $pegawai = Pegawai::create($request->only('nip', 'nama_pegawai', 'divisi'));

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
}
