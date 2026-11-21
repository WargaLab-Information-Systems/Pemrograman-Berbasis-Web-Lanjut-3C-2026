<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KelolaBukuController extends Controller
{
    /**
     * Halaman pengelolaan buku (tambah, ubah, hapus) untuk admin dan petugas.
     * Datanya diambil dan dikirim lewat Fetch API ke route /data/buku (dilindungi session + permission).
     */
    public function index(): View
    {
        return view('kelola.buku');
    }
}
