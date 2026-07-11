<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Dummy Tugas telah dihapus sesuai permintaan

        // Dummy Admin
        User::factory()->create([
            'name' => 'Super Administrator',
            'nip' => 'superadmin',
            'email' => 'superadmin@mail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        // Dummy Pegawai
        $pegawai = \App\Models\Pegawai::create([
            'nip' => '199001012020121001',
            'nama_pegawai' => 'Ahmad Pegawai',
            'divisi' => 'Pusvetma'
        ]);

        User::factory()->create([
            'name' => 'Ahmad Pegawai',
            'nip' => '199001012020121001',
            'email' => 'ahmad@pegawai.com',
            'password' => bcrypt('12345678'),
            'role' => 'pegawai',
            'pegawai_id' => $pegawai->id,
        ]);
    }
}
