<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Peminjaman;

class PeminjamanPolicy
{
    /**
     * Menentukan apakah user dapat melihat detail peminjaman.
     */
    public function view(User $user, $peminjaman): bool
    {
        // Admin dan Petugas boleh melihat semua data peminjaman
        if (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('petugas'))) {
            return true;
        }

        // Siswa hanya boleh melihat peminjaman miliknya sendiri (berdasarkan user_id)
        $peminjamanUserId = is_array($peminjaman) ? ($peminjaman['user_id'] ?? null) : ($peminjaman->user_id ?? null);
        return $peminjamanUserId == $user->id;
    }
}