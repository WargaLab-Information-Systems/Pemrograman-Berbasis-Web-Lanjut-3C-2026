<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BukuCariController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $judul = $request->query('judul', '');

        $hasil = DB::select('select * from bukus where judul = ?', [$judul]);

        return response()->json($hasil);
    }
}
