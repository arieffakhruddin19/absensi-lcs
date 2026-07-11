<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPosting extends Model
{
    protected $fillable = ['pegawai_id', 'posting_id', 'status_selesai', 'waktu_dikerjakan'];
}
