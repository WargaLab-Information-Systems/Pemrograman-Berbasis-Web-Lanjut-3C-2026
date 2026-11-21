<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Status peminjaman: diajukan -> dipinjam -> dikembalikan (atau ditolak).
     * Data lama: yang sudah punya tanggal_kembali dianggap dikembalikan,
     * selebihnya dianggap sedang dipinjam (default).
     */
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('status', 20)->default('dipinjam')->after('tanggal_kembali');
        });

        DB::table('peminjamans')
            ->whereNotNull('tanggal_kembali')
            ->update(['status' => 'dikembalikan']);
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
