<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::all();
        return view('buku.index', compact('buku'));
    }

   
    public function show($id)
    {
        
        $buku = Buku::findOrFail($id);

        
        return view('buku.show', compact('buku'));
    }
}