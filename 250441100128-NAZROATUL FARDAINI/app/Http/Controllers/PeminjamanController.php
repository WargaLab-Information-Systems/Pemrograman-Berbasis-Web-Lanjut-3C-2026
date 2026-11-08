<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // 1. SISWA: Mengajukan Peminjaman Buku (Data Asli User Login)
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $user = auth()->user();

        // Cari atau Buat Anggota baru DENGAN DATA USER YANG SEDANG LOGIN
        $anggota = Anggota::firstOrCreate(
            ['nama' => $user->name],
            [
                'nama'       => $user->name,
                'email'      => $user->email,
                'no_telepon' => '08123456789' // Nilai default agar tidak error NULL
            ]
        );

        Peminjaman::create([
            'anggota_id'      => $anggota->id,
            'buku_id'         => $request->buku_id,
            'tanggal_pinjam'  => now()->toDateString(),
            'tanggal_kembali' => null,
        ]);

        return redirect()->route('peminjaman.riwayat')->with('success', 'Berhasil melakukan peminjaman buku!');
    }

    // 3. PETUGAS & ADMIN: Melihat Semua Transaksi Peminjaman Asli
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    // 2. SISWA: Melihat Riwayat Peminjaman Pribadi
    public function riwayat()
    {
        $user = auth()->user();
        
        // Cari Anggota berdasarkan nama user login
        $anggota = Anggota::where('nama', $user->name)->first();
        $anggotaId = $anggota ? $anggota->id : 0;

        $peminjamans = Peminjaman::with('buku')
            ->where('anggota_id', $anggotaId)
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('peminjamans'));
    }

    // 4. PETUGAS & ADMIN: Menyetujui Pengembalian Buku
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Buku telah berhasil dikembalikan!');
    }

    // PETUGAS & ADMIN: Menampilkan Rekap Laporan Peminjaman
    public function laporan()
    {
        // Mengambil semua data peminjaman beserta relasi anggota dan bukunya
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();

        // Menghitung ringkasan statistik
        $totalPinjam = $peminjamans->count();
        $sedangDipinjam = $peminjamans->whereNull('tanggal_kembali')->count();
        $sudahKembali = $peminjamans->whereNotNull('tanggal_kembali')->count();

        return view('laporan.index', compact('peminjamans', 'totalPinjam', 'sedangDipinjam', 'sudahKembali'));
    }
}