<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    protected $fillable = [
        'judul_tugas', 
        'tanggal_tugas',
        'link_instagram', 
        'link_facebook', 
        'link_twitter', 
        'link_tiktok', 
        'link_youtube', 
        'batas_waktu',
        'sumber_posting'
    ];
}
