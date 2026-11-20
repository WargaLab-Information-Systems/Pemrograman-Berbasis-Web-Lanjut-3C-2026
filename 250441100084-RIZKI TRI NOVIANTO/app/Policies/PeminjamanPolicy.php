<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PeminjamanPolicy
{
    /**
     * Menentukan apakah user boleh melihat detail 1 transaksi peminjaman.
     * - Admin dan Petugas boleh melihat semua transaksi.
     * - Siswa hanya boleh melihat transaksi miliknya sendiri.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        return (int) $user->id === (int) $peminjaman->anggota_id;
    }

    /**
     * (Opsional) Menggantikan pengecekan kepemilikan manual pada halaman riwayat peminjaman.
     */
    public function viewHistory(User $user, int $targetUserId): bool
    {
        if ($user->hasAnyRole(['admin', 'petugas'])) {
            return true;
        }

        return (int) $user->id === $targetUserId;
    }
    // 15. Detail Peminjaman — DIAMANKAN DENGAN POLICY (Soal 4)
    public function showPeminjaman($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        // Memeriksa hak akses melalui PeminjamanPolicy@view 
        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }
}
