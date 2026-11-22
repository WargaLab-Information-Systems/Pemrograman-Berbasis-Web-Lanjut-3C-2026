<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
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
    public function before(User $user, $ability)
    {
        // Ambil data role dari database, ubah ke huruf kecil untuk amannya
        $role = strtolower(trim($user->role ?? ''));

        // Jika rolenya admin atau petugas, berikan akses penuh
        if (in_array($role, ['admin', 'petugas'])) {
            return true; 
        }
    }

    /**
     * Tentukan apakah user dapat melihat peminjaman (khusus siswa)
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Siswa hanya boleh melihat peminjaman miliknya sendiri
        return (int) $user->id === (int) $peminjaman->user_id;
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
