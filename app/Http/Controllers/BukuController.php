<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();

        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    // Perbaikan A2 (Soal 2b): Query Binding untuk mencegah SQL Injection
    public function cari(Request $request)
    {
        $judul = $request->query('judul');
        $buku = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);

        return response()->json($buku);
    }

    /**
     * Perbaikan A3 (Soal 2c): Menghapus data buku via route DELETE
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}