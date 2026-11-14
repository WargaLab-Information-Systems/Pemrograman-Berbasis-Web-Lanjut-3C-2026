<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function riwayat()
    {
        $user = Auth::user();
        if ($user->hasRole('siswa')) {
            $riwayats = Peminjaman::with('buku')->where('anggota_id', $user->id)->latest()->get();
        } else {
            $riwayats = Peminjaman::with(['user', 'buku'])->latest()->get();
        }
        return view('riwayat.index', compact('riwayats'));
    }

    public function laporan()
    {
        $laporans = Peminjaman::with(['user', 'buku'])->latest()->get();

        $totalTransaksi = Peminjaman::count();
        $totalSelesai = Peminjaman::where('status', 'dikembalikan')->count();

        return view('laporan.index', compact('laporans', 'totalTransaksi', 'totalSelesai'));
    }

    public function ajukan(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $existing = Peminjaman::where('anggota_id', Auth::id())
            ->where('buku_id', $request->buku_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Kamu sudah mengajukan atau sedang meminjam buku ini!'], 400);
        }

        Peminjaman::create([
            'anggota_id'      => Auth::id(),
            'buku_id'         => $request->buku_id,
            'tanggal_pinjam'  => Carbon::now()->format('Y-m-d'),
            'tanggal_kembali' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'status'          => 'pending',
        ]);

        return response()->json(['message' => 'Pengajuan peminjaman berhasil dikirim!']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,dipinjam,dikembalikan',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Status peminjaman berhasil diperbarui!']);
    }
}