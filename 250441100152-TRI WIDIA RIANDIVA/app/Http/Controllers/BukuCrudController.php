<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuCrudController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        
        return response()->json([
            'data' => $bukus
        ]); 
    }

    public function store(Request $request)
    {
       
    }
}