<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Tanda ?User membuat $user aman jika nilainya null (guest/belum login)
     */
    public function view(?User $user, Peminjaman $peminjaman): bool
    {
        // Jika belum login, otomatis tolak akses
        if (!$user) {
            return false;
        }

        // Admin dan Petugas bebas melihat semua peminjaman
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        // Siswa hanya bisa melihat miliknya sendiri
        return (int) $user->id === (int) $peminjaman->anggota_id;
    }
}