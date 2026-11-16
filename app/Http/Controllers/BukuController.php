<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
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

    public function create()
    {
        return view('buku.create', ['kategoris' => Kategori::all()]);
    }

    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$buku->judul}\" berhasil ditambahkan.");
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', ['buku' => $buku, 'kategoris' => Kategori::all()]);
    }

    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $buku->update($request->validated());

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$buku->judul}\" berhasil diperbarui.");
    }

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

    public function destroy(Buku $buku)
    {
        $judul = $buku->judul;

        try {
            $buku->delete();
        } catch (QueryException) {
            return back()->with('error', "Buku \"{$judul}\" masih memiliki data peminjaman sehingga tidak dapat dihapus.");
        }

        return redirect()->route('buku.index')
            ->with('success', "Buku \"{$judul}\" berhasil dihapus.");
    }
}
