<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = ['nip', 'nama_pegawai', 'tmt', 'tanggal_pensiun'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
