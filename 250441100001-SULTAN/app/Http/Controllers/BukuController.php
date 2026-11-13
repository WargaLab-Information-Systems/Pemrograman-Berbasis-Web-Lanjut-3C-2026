<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 

class BukuController extends Controller 
{ 
    private $books = [ 
        ['id' => 1, 'judul' => 'Bisnis Digital', 'penulis' => 'Sultan', 'tahun' => 2024, 'kategori' => 'Teknologi'], 
        ['id' => 2, 'judul' => 'Pemrograman Web', 'penulis' => 'Safira', 'tahun' => 2025, 'kategori' => 'Akademik'], 
        ['id' => 3, 'judul' => 'Belajar Laravel MVC', 'penulis' => 'Irfan', 'tahun' => 2026, 'kategori' => 'Akademik'], 
        ['id' => 4, 'judul' => 'Basis Data', 'penulis' => 'Gerhant', 'tahun' => 2023, 'kategori' => 'Akademik'], 
        ['id' => 5, 'judul' => 'Desain Canva', 'penulis' => 'Deren', 'tahun' => 2025, 'kategori' => 'Desain'], 
    ]; 

    public function index() 
    { 
        return view('buku.index', ['buku' => $this->books]); 
    } 

    public function show($id)
    {
        $buku = collect($this->books)->firstWhere('id', $id);

        if (!$buku) {
            abort(404);
        }

        return view('buku.show', compact('buku'));
    }
}