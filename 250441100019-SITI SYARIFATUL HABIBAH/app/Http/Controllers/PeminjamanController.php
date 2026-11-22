<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;


class PeminjamanController extends Controller
{
    public function show($id): View
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Gate::authorize('view', $peminjaman);

        $peminjaman->load(['buku.kategori', 'anggota']);

        return view('peminjaman.show', compact('peminjaman'));
    }
}
