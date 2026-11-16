<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(): View
    {
        $bukuList = Buku::with('kategori')->latest()->get();

        return view('buku.index', compact('bukuList'));
    }

    public function show(int $id): View
    {
        $buku = Buku::with('kategori')->find($id);

        return view('buku.show', compact('buku'));
    }
}
