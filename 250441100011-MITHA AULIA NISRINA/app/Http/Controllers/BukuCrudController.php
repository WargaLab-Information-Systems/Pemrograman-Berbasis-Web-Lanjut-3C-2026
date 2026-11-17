<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\View\View;

class BukuCrudController extends Controller
{
    /**
     * Menampilkan halaman kelola buku.
     *
     * Proses tambah, ubah, dan hapus buku tetap menggunakan
     * RESTful API yang sudah dibuat pada modul sebelumnya.
     */
    public function index(): View
    {
        $daftarKategori = Kategori::orderBy('nama')->get();

        return view('kelola-buku.index', compact('daftarKategori'));
    }
}