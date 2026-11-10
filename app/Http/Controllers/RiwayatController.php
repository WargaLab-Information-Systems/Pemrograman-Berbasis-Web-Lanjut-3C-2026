<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    /** Status peminjaman milik sendiri (yang masih berjalan). */
    public function status(Request $request)
    {
        return view('riwayat.index', [
            'judul' => 'Status Peminjaman Saya',
            'pemilik' => $request->user(),
            'peminjamans' => $this->milik($request->user())
                ->whereIn('status', [Peminjaman::STATUS_DIAJUKAN, Peminjaman::STATUS_DIPINJAM])
                ->get(),
        ]);
    }

    /** Riwayat peminjaman milik sendiri. */
    public function saya(Request $request)
    {
        return view('riwayat.index', [
            'judul' => 'Riwayat Peminjaman Saya',
            'pemilik' => $request->user(),
            'peminjamans' => $this->milik($request->user())->get(),
        ]);
    }

    /**
     * Riwayat milik user tertentu. Pemeriksaan identitas: selain dirinya sendiri,
     * hanya yang berhak "kelola peminjaman" (admin/petugas) yang boleh melihat.
     * Siswa yang mengganti ID di URL akan mendapat 403.
     */
    public function user(Request $request, User $user)
    {
        abort_unless(
            $user->is($request->user()) || $request->user()->can('kelola peminjaman'),
            403,
            'Anda tidak berhak melihat riwayat peminjaman pengguna lain.'
        );

        return view('riwayat.index', [
            'judul' => 'Riwayat Peminjaman '.$user->name,
            'pemilik' => $user,
            'peminjamans' => $this->milik($user)->get(),
        ]);
    }

    private function milik(User $user)
    {
        return Peminjaman::with('buku')
            ->whereHas('anggota', fn ($q) => $q->where('user_id', $user->id))
            ->latest('id');
    }
}
