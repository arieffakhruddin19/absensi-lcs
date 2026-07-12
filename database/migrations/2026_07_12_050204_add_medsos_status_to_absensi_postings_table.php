<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('absensi_postings', function (Blueprint $table) {
            $table->boolean('ig_like')->default(false)->after('posting_id');
            $table->boolean('ig_comment')->default(false)->after('ig_like');
            $table->boolean('ig_share')->default(false)->after('ig_comment');
            
            $table->boolean('fb_like')->default(false)->after('ig_share');
            $table->boolean('fb_comment')->default(false)->after('fb_like');
            $table->boolean('fb_share')->default(false)->after('fb_comment');
            
            $table->boolean('tw_like')->default(false)->after('fb_share');
            $table->boolean('tw_comment')->default(false)->after('tw_like');
            $table->boolean('tw_share')->default(false)->after('tw_comment');
            
            $table->boolean('tt_like')->default(false)->after('tw_share');
            $table->boolean('tt_comment')->default(false)->after('tt_like');
            $table->boolean('tt_share')->default(false)->after('tt_comment');
            
            $table->boolean('yt_like')->default(false)->after('tt_share');
            $table->boolean('yt_comment')->default(false)->after('yt_like');
            $table->boolean('yt_share')->default(false)->after('yt_comment');
            
            $table->timestamp('waktu_instagram')->nullable()->after('yt_share');
            $table->timestamp('waktu_facebook')->nullable()->after('waktu_instagram');
            $table->timestamp('waktu_twitter')->nullable()->after('waktu_facebook');
            $table->timestamp('waktu_tiktok')->nullable()->after('waktu_twitter');
            $table->timestamp('waktu_youtube')->nullable()->after('waktu_tiktok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_postings', function (Blueprint $table) {
            $table->dropColumn([
                'ig_like', 'ig_comment', 'ig_share',
                'fb_like', 'fb_comment', 'fb_share',
                'tw_like', 'tw_comment', 'tw_share',
                'tt_like', 'tt_comment', 'tt_share',
                'yt_like', 'yt_comment', 'yt_share',
                'waktu_instagram', 'waktu_facebook', 'waktu_twitter', 'waktu_tiktok', 'waktu_youtube'
            ]);
        });
    }
};
