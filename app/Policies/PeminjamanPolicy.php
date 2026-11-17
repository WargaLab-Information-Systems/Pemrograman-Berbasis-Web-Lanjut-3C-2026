<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
   public function view(User $user, Peminjaman $peminjaman): bool
{
    if ($user->hasAnyRole(['admin', 'petugas'])) {
        return true;
    }

    // Pastikan relasi 'anggota' ada, lalu bandingkan emailnya dengan user yang login
    return $user->hasRole('siswa') 
        && $peminjaman->anggota 
        && $peminjaman->anggota->email === $user->email;
}
}
