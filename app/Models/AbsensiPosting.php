<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPosting extends Model
{
    protected $fillable = [
        'pegawai_id', 'posting_id', 
        'ig_like', 'ig_comment', 'ig_share',
        'fb_like', 'fb_comment', 'fb_share',
        'tw_like', 'tw_comment', 'tw_share',
        'tt_like', 'tt_comment', 'tt_share',
        'yt_like', 'yt_comment', 'yt_share',
        'waktu_instagram', 'waktu_facebook', 'waktu_twitter', 'waktu_tiktok', 'waktu_youtube',
        'status_selesai', 'waktu_dikerjakan'
    ];
}
