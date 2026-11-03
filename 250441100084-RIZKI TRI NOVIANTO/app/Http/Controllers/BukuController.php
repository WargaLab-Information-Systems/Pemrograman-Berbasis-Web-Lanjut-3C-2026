<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        
        return view('buku.index');
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    }
}