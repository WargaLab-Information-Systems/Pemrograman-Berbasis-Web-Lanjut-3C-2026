<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menghubungkan akun login (users) dengan data anggota perpustakaan.
     * Dipakai agar siswa hanya bisa melihat riwayat peminjaman miliknya sendiri.
     */
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
