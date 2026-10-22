<?php

namespace App\Http\Controllers;

use App\Models\Buku; // Import Model Buku
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        // Mengambil semua data buku dari database beserta relasi kategorinya
        $bukuList = Buku::with('kategori')->get();

        return view('buku.index', compact('bukuList'));
    }

    public function show(int $id)
    {
        // Mengambil 1 data buku berdasarkan ID dari database (akan 404 jika tidak ditemukan)
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('buku.show', compact('buku'));
    }
}

