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
            $table->boolean('diselesaikan_oleh_admin')->default(false)->after('status_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_postings', function (Blueprint $table) {
            $table->dropColumn('diselesaikan_oleh_admin');
        });
    }
};
