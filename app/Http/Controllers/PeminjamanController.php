<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
    private const STATUS_AKTIF = [Peminjaman::STATUS_DIAJUKAN, Peminjaman::STATUS_DIPINJAM];

    public function index(Request $request)
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function pengembalian()
    {
        $dipinjam = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->orderBy('tanggal_pinjam')
            ->get();

        $dikembalikan = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIKEMBALIKAN)
            ->latest('tanggal_kembali')
            ->limit(15)
            ->get();

        return view('peminjaman.pengembalian', compact('dipinjam', 'dikembalikan'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', ['peminjaman' => $peminjaman->load(['anggota', 'buku.kategori', 'pemroses'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'buku_id' => ['required', 'integer', 'exists:bukus,id'],
        ]);

        $anggota = $request->user()->ensureAnggota();
        $buku = Buku::findOrFail($data['buku_id']);

        $sudahAda = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', self::STATUS_AKTIF)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', "Anda masih memiliki pengajuan/peminjaman aktif untuk buku \"{$buku->judul}\".");
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::STATUS_DIAJUKAN,
        ]);

        return redirect()->route('peminjaman.status')
            ->with('success', "Pengajuan peminjaman \"{$buku->judul}\" berhasil dikirim.");
    }

    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($tolak = $this->tolakJikaTidakValid($request->user(), $peminjaman, Peminjaman::STATUS_DIAJUKAN)) {
            return $tolak;
        }

        $dipakai = Peminjaman::where('buku_id', $peminjaman->buku_id)
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->exists();

        if ($dipakai) {
            return back()->with('error', 'Buku ini sedang dipinjam anggota lain.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
            'diproses_oleh' => $request->user()->id,
        ]);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($tolak = $this->tolakJikaTidakValid($request->user(), $peminjaman, Peminjaman::STATUS_DIAJUKAN)) {
            return $tolak;
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DITOLAK,
            'diproses_oleh' => $request->user()->id,
        ]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function kembali(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($tolak = $this->tolakJikaTidakValid($request->user(), $peminjaman, Peminjaman::STATUS_DIPINJAM)) {
            return $tolak;
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
            'diproses_oleh' => $request->user()->id,
        ]);

        return back()->with('success', 'Pengembalian buku dicatat.');
    }

    private function tolakJikaTidakValid(User $user, Peminjaman $peminjaman, string $statusDiharapkan): ?RedirectResponse
    {
        if ($peminjaman->dimilikiOleh($user)) {
            return back()->with('error', 'Anda tidak dapat memproses peminjaman milik Anda sendiri.');
        }

        if ($peminjaman->status !== $statusDiharapkan) {
            return back()->with('error', "Status peminjaman saat ini \"{$peminjaman->status}\"; aksi tidak dapat dilakukan.");
        }

        return null;
    }
}
