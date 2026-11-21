<?php

namespace App\Support;

use App\Models\Anggota;
use App\Models\User;

/**
 * Pembantu untuk menghubungkan akun login (User) dengan data Anggota.
 * Menggunakan forceFill supaya tidak bergantung pada $fillable model Anggota.
 */
class Anggotas
{
    /** Data anggota milik user yang sedang dicari (null kalau belum ada). */
    public static function untuk(User $user): ?Anggota
    {
        return Anggota::where('user_id', $user->id)->first();
    }

    /**
     * Pastikan user punya data anggota.
     * - $hubungkanEmail = true: kalau ada anggota lama dengan email sama yang belum terhubung
     *   ke akun mana pun, anggota itu dihubungkan (hanya dipakai oleh seeder dan admin).
     * - Mengembalikan null kalau email sudah dipakai anggota lain dan tidak boleh dihubungkan.
     */
    public static function pastikan(User $user, ?string $telepon = null, bool $hubungkanEmail = false): ?Anggota
    {
        $anggota = self::untuk($user);
        if ($anggota) {
            return $anggota;
        }

        $lama = Anggota::where('email', $user->email)->first();
        if ($lama) {
            if ($hubungkanEmail && $lama->user_id === null) {
                $lama->user_id = $user->id;
                $lama->save();

                return $lama;
            }

            return null;
        }

        $baru = new Anggota();
        $baru->forceFill([
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'no_telepon' => $telepon ?: '-',
        ])->save();

        return $baru;
    }
}
