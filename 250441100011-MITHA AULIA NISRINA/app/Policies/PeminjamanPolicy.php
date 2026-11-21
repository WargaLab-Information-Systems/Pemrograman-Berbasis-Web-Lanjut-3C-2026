<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Menentukan siapa yang boleh melihat detail peminjaman.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Admin dan Petugas dapat melihat semua peminjaman.
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        // Siswa hanya dapat melihat peminjaman miliknya sendiri.
        return $peminjaman->anggota_id === $user->anggota?->id;
    }
}
