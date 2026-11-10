<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Helper internal untuk cek role Siswa (Aman dari error Spatie)
    private function checkIsSiswa($user)
    {
        if (!$user) return false;
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('siswa');
        }
        return isset($user->roles) && $user->roles->pluck('name')->contains('siswa');
    }

    // Halaman Kelola / Pengajuan Peminjaman
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $this->checkIsSiswa($user);

        if ($isSiswa) {
            $peminjaman = Peminjaman::with(['buku'])->where('anggota_id', $user->id)->latest()->get();
            $anggotaList = collect();
        } else {
            $peminjaman = Peminjaman::with(['anggota', 'buku'])->latest()->get();
            $anggotaList = User::all();
        }

        $bukuList = Buku::all();

        return view('peminjaman.index', compact('peminjaman', 'bukuList', 'anggotaList'));
    }

    // Halaman Riwayat Saya (Khusus Siswa / User Logged In)
    public function riwayatSaya()
    {
        $user = Auth::user();

        $riwayat = Peminjaman::with(['buku'])
            ->where('anggota_id', $user->id)
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('riwayat'));
    }

    // 1. Simpan Peminjaman (Otomatis Menggunakan Account Login)
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $user = Auth::user();
        $isSiswa = $this->checkIsSiswa($user);

        // Jika Siswa, status 'menunggu' (butuh ACC Admin/Guru)
        // Jika Admin/Guru, status otomatis 'dipinjam'
        $status = $isSiswa ? 'menunggu' : 'dipinjam';

        // Peminjam SELALU user yang sedang login saat ini
        Peminjaman::create([
            'anggota_id' => $user->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => $status,
        ]);

        $pesan = $isSiswa 
            ? 'Pengajuan peminjaman berhasil terkirim! Menunggu persetujuan Admin/Guru.' 
            : 'Peminjaman berhasil dicatat!';

        return redirect()->back()->with('success', $pesan);
    }

    // 2. Setujui / ACC Peminjaman (Khusus Admin/Guru)
    public function setujui($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dipinjam'
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil disetujui!');
    }

    // 3. Tolak Peminjaman (Khusus Admin/Guru)
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    // 4. Pengembalian Buku
    public function pengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'dikembalikan'
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

    // 5. Halaman Laporan Perpustakaan & Persetujuan (Khusus Admin & Guru)
    public function laporan()
    {
        $persetujuanPinjam = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        $semuaTransaksi = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->get();

        $totalPinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalKembali = Peminjaman::where('status', 'dikembalikan')->count();

        return view('laporan', compact(
            'persetujuanPinjam',
            'semuaTransaksi',
            'totalPinjam',
            'totalKembali'
        ));
    }

    // 6. Riwayat Berdasarkan ID (Memicu 403 Forbidden Jika Diintip Siswa Lain)
    public function riwayatBerdasarkanId($id)
    {
        $user = Auth::user();
        $isSiswa = $this->checkIsSiswa($user);

        // Jika yang membuka adalah Siswa DAN mencoba melihat ID akun milik orang lain -> LEMPAR 403
        if ($isSiswa && (string) $id !== (string) $user->id) {
            abort(403, 'User does not have the right roles.');
        }

        $riwayat = Peminjaman::with(['buku'])
            ->where('anggota_id', $id)
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('riwayat'));
    }

    // CELAH A4 (IDOR): Mengambil data peminjaman berdasarkan ID tanpa melakukan verifikasi kepemilikan user.
    public function show($id)
    {
        // Menggunakan relasi 'anggota' dan 'buku' (Rentan IDOR)
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        return response()->json($peminjaman);
    }

}