<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class PeminjamanController extends Controller
{
    // Siswa mengajukan peminjaman buku
    public function store($id)
    {
        Peminjaman::create([
            'user_id' => auth()->id(), // Menggunakan ID user yang sedang login
            'buku_id' => $id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam',
        ]);

        return redirect()->route('siswa.riwayat')->with('success', 'Buku berhasil dipinjam!');
    }

    // Menampilkan daftar riwayat peminjaman siswa yang sedang login
    public function riwayat()
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())->get();
        
        return view('siswa.riwayat', compact('peminjamans'));
    }

    // Siswa mengembalikan buku miliknya sendiri
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id()) // Disesuaikan dari anggota_id ke user_id
            ->firstOrFail();

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    // Admin & Petugas melihat seluruh data peminjaman
    public function indexAdmin()
    {
        // Relasi diganti ke 'user' sesuai migrasi ke tabel users
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    // Admin & Petugas memproses status peminjaman
    public function prosesAdmin($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        return back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    // public function show($id)
    // {
    //     // Menggunakan findOrFail($id) murni tanpa Gate/pemeriksaan kepemilikan agar celah IDOR teruji
    //     $peminjaman = Peminjaman::findOrFail($id); 
        
    //     return view('peminjaman.show', compact('peminjaman'));
    // }
//     public function show($id)
// {
//     // Ambil data berdasarkan ID secara langsung tanpa pemeriksaan kepemilikan/Gate (Celah IDOR)
//     $peminjaman = Peminjaman::findOrFail($id); 
    
//     return view('peminjaman.show', compact('peminjaman'));
// }

        public function show($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }
}