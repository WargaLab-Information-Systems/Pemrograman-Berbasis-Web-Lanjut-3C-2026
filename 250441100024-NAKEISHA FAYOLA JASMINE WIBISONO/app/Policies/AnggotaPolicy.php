<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\User;

class AnggotaPolicy
{
    /**
     * Riwayat peminjaman hanya boleh dilihat oleh pemiliknya (dicek lewat identitas user yang login),
     * atau oleh staf yang berhak mengelola peminjaman (admin/petugas).
     */
    public function viewRiwayat(User $user, Anggota $anggota): bool
    {
        if ($anggota->user_id !== null && (int) $anggota->user_id === (int) $user->id) {
            return true;
        }

        return $user->can('kelola-peminjaman');
    }
}
