<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_PETUGAS])) {
            return true;
        }

        return $peminjaman->anggota?->user_id === $user->id;
    }
}
