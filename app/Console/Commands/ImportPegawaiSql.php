<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ImportPegawaiSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pegawai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data pegawai from pegawai.sql in root directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sqlFile = base_path('pegawai.sql');

        if (!File::exists($sqlFile)) {
            $this->error("File pegawai.sql not found at {$sqlFile}");
            return;
        }

        $this->info("Membersihkan data pegawai dan user yang ada...");
        
        // Nonaktifkan foreign key checks untuk sementara agar bisa delete semua
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus semua data pegawai
        Pegawai::truncate();
        
        // Hapus semua user dengan role pegawai
        User::where('role', 'pegawai')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info("Membaca file SQL...");
        $sqlContent = File::get($sqlFile);

        // Regex untuk menangkap NIP, Nama, TMT, Tanggal Pensiun
        // Format insert: (1, '196706181993032002', 'drh  Wriningati, M.Kes.', '2000-01-01', '2030-12-31', ... )
        $pattern = "/\(\d+,\s*'([^']+)',\s*'([^']+)',\s*'([^']+)',\s*'([^']+)'/";
        
        preg_match_all($pattern, $sqlContent, $matches, PREG_SET_ORDER);

        $count = 0;
        $this->info("Menemukan " . count($matches) . " record. Memulai import...");

        foreach ($matches as $match) {
            $nip = $match[1];
            $nama = $match[2];
            $tmt = $match[3] === 'NULL' ? null : $match[3];
            $tanggal_pensiun = $match[4] === 'NULL' ? null : $match[4];

            // Cek apakah NIP sudah ada
            $existing = Pegawai::where('nip', $nip)->first();
            
            if (!$existing) {
                // Buat data Pegawai
                $pegawai = Pegawai::create([
                    'nip' => $nip,
                    'nama_pegawai' => $nama,
                    'tmt' => $tmt,
                    'tanggal_pensiun' => $tanggal_pensiun,
                ]);

                // Buat data User
                User::create([
                    'name' => $nama,
                    'nip' => $nip,
                    'password' => Hash::make('12345678'),
                    'role' => 'pegawai',
                    'pegawai_id' => $pegawai->id,
                ]);

                $count++;
            }
        }

        $this->info("Berhasil mengimpor {$count} data pegawai dan membuatkan akun login.");
    }
}
