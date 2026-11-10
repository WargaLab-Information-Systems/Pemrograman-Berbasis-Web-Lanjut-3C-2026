<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
 
class PeminjamanPolicy
{
    /**
     * Data peminjaman hanya boleh dilihat oleh pemiliknya (cek identitas),
     * atau oleh pengguna yang berhak mengelola peminjaman (admin/petugas).
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        return $peminjaman->dimilikiOleh($user) || $user->can('kelola peminjaman');
    }
}
