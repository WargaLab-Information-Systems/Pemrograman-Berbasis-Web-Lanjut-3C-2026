<?php

namespace App\Http\Controllers;
use App\Models\Buku;

class BukuController extends Controller
{
  public function index()
    {
        $buku = Buku::all();

        return view('buku', compact('buku'));
    }
    public function show($id)
    {
        $detail = Buku::find($id);

        return view('detail', compact('detail'));
    }
}
