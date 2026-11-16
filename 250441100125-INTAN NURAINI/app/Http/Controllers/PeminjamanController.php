<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->get();

        return view('peminjaman.index', compact('peminjamans'));
    }

    // Menampilkan detail peminjaman sesuai hak akses pengguna
    public function show(int $id)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->findOrFail($id);

        $roles = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', get_class($user))
            ->pluck('roles.name')
            ->toArray();

        $bolehLihat = false;

        if (in_array('Admin', $roles) || in_array('Petugas', $roles)) {
            $bolehLihat = true;
        } elseif (
            in_array('Siswa', $roles)
            && $peminjaman->anggota !== null
            && (int) $peminjaman->anggota->user_id === (int) $user->id
        ) {
            $bolehLihat = true;
        }

        abort_unless($bolehLihat, 403);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'Menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $peminjaman->update([
            'status' => $request->status,
        ]);

        $pesan = $request->status === 'Disetujui'
            ? 'Pengajuan peminjaman berhasil disetujui.'
            : 'Pengajuan peminjaman berhasil ditolak.';

        return back()->with('success', $pesan);
    }
}
