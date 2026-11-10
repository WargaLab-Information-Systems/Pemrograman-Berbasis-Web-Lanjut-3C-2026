<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
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
     * [A2 - RENTAN] SQL Injection: nilai "judul" digabungkan langsung ke string SQL.
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

        return response()->json($hasil);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('buku.show', compact('buku'));
    }
}
