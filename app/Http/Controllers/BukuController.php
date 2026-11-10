<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();

        return view('buku.index', compact('buku'));
    }

    public function show(int $id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('buku.show', compact('buku'));
    }

    /**
     * METHOD SOAL A3: Menghapus data buku dari database
     */
    public function destroy($id)
    {
        // Cari data buku berdasarkan ID lalu hapus
        $buku = Buku::findOrFail($id);
        $buku->delete();

        // Redirect kembali ke halaman daftar buku
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}