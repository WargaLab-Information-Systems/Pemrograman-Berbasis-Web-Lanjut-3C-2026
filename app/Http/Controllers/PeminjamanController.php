<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
    // Helper internal untuk cek role Siswa
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

    // Halaman Riwayat Saya
    public function riwayatSaya()
    {
        $user = Auth::user();

        $riwayat = Peminjaman::with(['buku'])
            ->where('anggota_id', $user->id)
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('riwayat'));
    }

    // 1. Simpan Peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $user = Auth::user();
        $isSiswa = $this->checkIsSiswa($user);
        $status = $isSiswa ? 'menunggu' : 'dipinjam';

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

    // 2. Method Show (Soal 4: Menutup Celah IDOR A4 dengan Policy)
    // public function show($id)
    // {
    //     $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

    //     // Memanggil Policy view
    //     Gate::authorize('view', $peminjaman);

    //     // Jika request menginginkan JSON (seperti pengujian API/modul)
    //     if (request()->wantsJson()) {
    //         return response()->json($peminjaman);
    //     }

    //     return view('peminjaman.show', compact('peminjaman'));
    // }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        // Soal 4: Proteksi akses detail peminjaman menggunakan Policy
        Gate::authorize('view', $peminjaman);

        // Kembalikan langsung respons JSON agar tidak membutuhkan file view Blade
        return response()->json($peminjaman);
    }

    // 3. Setujui Peminjaman
    public function setujui($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'dipinjam']);

        return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil disetujui!');
    }

    // 4. Tolak Peminjaman
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    // 5. Pengembalian Buku
    public function pengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'dikembalikan']);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

    // 6. Halaman Laporan Perpustakaan
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

    // 7. Riwayat Berdasarkan ID
    public function riwayatBerdasarkanId($id)
    {
        $user = Auth::user();
        $isSiswa = $this->checkIsSiswa($user);

        if ($isSiswa && (string) $id !== (string) $user->id) {
            abort(403, 'User does not have the right roles.');
        }

        $riwayat = Peminjaman::with(['buku'])
            ->where('anggota_id', $id)
            ->latest()
            ->get();

        return view('peminjaman.riwayat', compact('riwayat'));
    }
}