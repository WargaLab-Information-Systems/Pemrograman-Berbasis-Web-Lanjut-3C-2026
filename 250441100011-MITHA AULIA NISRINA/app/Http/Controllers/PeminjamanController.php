<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan data untuk pengelolaan peminjaman.
     */
    public function index(): View
    {
        $peminjamans = Peminjaman::with([
            'anggota',
            'buku',
        ])
            ->latest('id')
            ->get();

        return view(
            'peminjaman.index',
            compact('peminjamans')
        );
    }


    /**
     * Menampilkan buku yang sedang dipinjam
     * dan dapat diproses pengembaliannya.
     */
    public function returns(): View
    {
        $peminjamans = Peminjaman::with([
            'anggota',
            'buku',
        ])
            ->where('status', 'dipinjam')
            ->whereNull('tanggal_kembali')
            ->latest('id')
            ->get();

        return view(
            'pengembalian.index',
            compact('peminjamans')
        );
    }


    /**
     * Mengajukan peminjaman buku oleh pengguna yang sedang login.
     */
    public function store(Buku $buku): RedirectResponse
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Pastikan Pengguna Memiliki Data Anggota
        |--------------------------------------------------------------------------
        */

        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()
                ->route('buku.show', $buku->id)
                ->with(
                    'error',
                    'Data anggota belum tersedia. Peminjaman tidak dapat diajukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cegah Pengajuan Buku yang Sama Secara Bersamaan
        |--------------------------------------------------------------------------
        */

        $peminjamanAktif = Peminjaman::where(
            'anggota_id',
            $anggota->id
        )
            ->where(
                'buku_id',
                $buku->id
            )
            ->whereIn(
                'status',
                [
                    'menunggu',
                    'dipinjam',
                ]
            )
            ->exists();

        if ($peminjamanAktif) {
            return redirect()
                ->route('buku.show', $buku->id)
                ->with(
                    'error',
                    'Buku ini masih dalam pengajuan atau peminjaman Anda.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Pengajuan Peminjaman
        |--------------------------------------------------------------------------
        */

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('buku.show', $buku->id)
            ->with(
                'success',
                'Pengajuan peminjaman buku berhasil dikirim.'
            );
    }

    /**
     * Menampilkan detail peminjaman untuk pengujian IDOR A4.
     */
    public function show($id): View
    {
        $peminjaman = Peminjaman::with([
            'anggota',
            'buku',
        ])->findOrFail($id);

        // Memeriksa hak akses menggunakan PeminjamanPolicy.
        Gate::authorize('view', $peminjaman);

        return view(
            'peminjaman.show',
            compact('peminjaman')
        );
    }
    /**
     * Menyetujui pengajuan peminjaman.
     */
    public function approve(
        Peminjaman $peminjaman
    ): RedirectResponse {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()
                ->route('peminjaman.index')
                ->with(
                    'error',
                    'Peminjaman ini tidak dapat disetujui.'
                );
        }

        $peminjaman->update([
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'dipinjam',
        ]);

        return redirect()
            ->route('peminjaman.index')
            ->with(
                'success',
                'Pengajuan peminjaman berhasil disetujui.'
            );
    }


    /**
     * Mencatat pengembalian buku.
     */
    public function returnBook(
        Peminjaman $peminjaman
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Hanya Peminjaman Aktif yang Dapat Dikembalikan
        |--------------------------------------------------------------------------
        */

        if (
            $peminjaman->status !== 'dipinjam' ||
            $peminjaman->tanggal_kembali !== null
        ) {
            return redirect()
                ->route('pengembalian.index')
                ->with(
                    'error',
                    'Buku ini tidak dapat diproses sebagai pengembalian.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Catat Pengembalian
        |--------------------------------------------------------------------------
        */

        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
            'status' => 'dikembalikan',
        ]);

        return redirect()
            ->route('pengembalian.index')
            ->with(
                'success',
                'Pengembalian buku berhasil dicatat.'
            );
    }
}