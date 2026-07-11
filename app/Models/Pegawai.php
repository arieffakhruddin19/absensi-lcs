<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = ['nip', 'nama_pegawai', 'divisi'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
