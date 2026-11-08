<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Support\Anggotas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Setelah login, arahkan pengguna ke dashboard sesuai permission miliknya. */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->can('akses-dashboard-admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('akses-dashboard-petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    }

    public function admin(): View
    {
        return view('dashboard.admin', $this->ringkasan() + [
            'totalPengguna' => User::count(),
        ]);
    }

    public function petugas(): View
    {
        return view('dashboard.petugas', $this->ringkasan());
    }

    public function siswa(Request $request): View
    {
        $anggota = Anggotas::untuk($request->user());

        $status = $anggota
            ? Peminjaman::where('anggota_id', $anggota->id)
                ->select('status', DB::raw('count(*) as jumlah'))
                ->groupBy('status')
                ->pluck('jumlah', 'status')
            : collect();

        return view('dashboard.siswa', compact('anggota', 'status'));
    }

    private function ringkasan(): array
    {
        return [
            'totalBuku' => Buku::count(),
            'totalAnggota' => Anggota::count(),
            'status' => Peminjaman::select('status', DB::raw('count(*) as jumlah'))
                ->groupBy('status')
                ->pluck('jumlah', 'status'),
        ];
    }
}
