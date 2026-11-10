<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Admin dan petugas boleh melihat semua peminjaman;
     * siswa hanya boleh melihat peminjaman miliknya sendiri (cek kepemilikan data).
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_PETUGAS])) {
            return true;
        }

        return $peminjaman->anggota?->user_id === $user->id;
    }
}
