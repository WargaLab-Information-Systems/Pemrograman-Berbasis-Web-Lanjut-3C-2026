<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $bukus = Buku::with('kategori')->latest('id')->paginate(10);

        return view('buku.index', compact('kategoris', 'bukus'));
    }

    /**
     * Pencarian buku berdasarkan judul (query dengan parameter binding).
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);

        return response()->json($hasil);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('buku.show', compact('buku'));
    }

    /** Hapus buku: hanya lewat DELETE + token CSRF. */
    public function destroy(Buku $buku)
    {
        $judul = $buku->judul;

        try {
            $buku->delete();
        } catch (QueryException) {
            // Buku yang masih punya riwayat peminjaman tidak boleh dihapus (foreign key).
            return back()->with('error', "Buku \"{$judul}\" masih memiliki data peminjaman sehingga tidak dapat dihapus.");
        }

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$judul}\" berhasil dihapus.");
    }
}
