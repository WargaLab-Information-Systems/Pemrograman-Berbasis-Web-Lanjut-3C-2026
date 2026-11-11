<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Auth\Access\Response;

class PeminjamanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('petugas')) {
            return true;
        }
        $anggota = Anggota::where('email', $user->email)->first();
        return $anggota !== null && $anggota->id === $peminjaman->anggota_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Peminjaman $peminjaman): bool
    {
        return false;
    }
}
