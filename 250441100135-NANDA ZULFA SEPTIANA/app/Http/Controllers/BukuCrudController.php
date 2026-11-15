<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Atau gunakan 'use PDF;' jika memakai alias lama

class BukuCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $bukus = Buku::with('kategori')->get();
    //     // return view ('kelola-buku.index', compact('bukus'));
    //     return response()->json(['data'=>$bukus]);
    // }

    public function index()
{
    // $bukus = Buku::paginate(5);
    // $kategoris = Kategori::all();
    // $bukus = \App\Models\Buku::all(); // Pastikan modelnya terpanggil
    // return view('dashboard', compact('bukus'));
    $bukus = \App\Models\Buku::all(); 

    // Tambahan data statistik/ringkasan untuk laporan di dashboard admin
    $totalBuku = \App\Models\Buku::count();
    $totalPeminjaman = \App\Models\Peminjaman::count();
    $totalSiswa = \App\Models\User::where('role', 'siswa')->count();

    // Kirim seluruh variabel ke view dashboard
    return view('dashboard', compact('bukus', 'totalBuku', 'totalPeminjaman', 'totalSiswa'));
    // PASTIKAN INI 'dashboard', BUKAN 'buku.create'
    // return view('dashboard', compact('bukus', 'kategoris')); 
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $kategoris = Kategori::all();
    
    // Ini baru untuk halaman tambah buku
    return view('buku.create', compact('kategoris')); 
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi sesuai ketentuan: wajib diisi, minimal 3 karakter untuk teks, dan max tahun sekarang (2026)
    // $request->validate([
    //     'judul'        => ['required', 'string'],
    //     'penulis'      => ['required', 'string', 'min:3'],
    //     'penerbit'     => ['required', 'string', 'min:3'],
    //     'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
    // ], [
    //     'required'     => ':attribute wajib diisi.',
    //     'min'          => ':attribute minimal harus terdiri dari 3 karakter.',
    //     'max'          => ':attribute tidak boleh melebihi tahun ' . date('Y') . '.',
    //     'integer'      => ':attribute harus berupa angka.',
    // ]);

    // // Proses simpan data ke database jika lolos validasi
    // Buku::create($request->all());

    // return redirect()->route('dashboard')->with('success', 'Data buku berhasil ditambahkan!');

    $validated = $request->validate([
    'judul'        => ['required', 'string'], // Pastikan tidak ada aturan min/max atau regex aneh
    'penulis'      => ['required', 'string'],
    'tahun_terbit' => ['required', 'integer'],
    'kategori_id'  => ['nullable'], // Buat nullable dulu jika belum dipilih
], [
    'required' => ':attribute wajib diisi.',
]);
Buku::create($request->all());

    return redirect()->route('dashboard')->with('success', 'Data buku berhasil ditambahkan!');
}

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all(); 

        return view('buku.edit', compact('buku', 'kategoris'));
    }


    // Menyimpan perubahan data ke database
//     public function update(Request $request, $id)
// {
//     try {
//         $validated = $request->validate([
//             'judul' => ['required', 'string', 'min:3'],
//             'penulis' => ['required', 'string', 'min:3'],
//             'penerbit' => ['required', 'string', 'min:3'],
//             'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:2100'],
//         ]);

//         $buku = Buku::findOrFail($id);
//         $buku->update($validated);

//         return redirect()->route('dashboard')->with('success', 'Data buku berhasil diperbarui!');

//     } catch (\Exception $e) {
//         return back()->withInput()->with('error', 'Gagal memperbarui data! Terjadi kesalahan.');
//     }
// }
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'judul'        => ['required', 'string', 'min:3'],
        'penulis'      => ['required', 'string', 'min:3'],
        'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:2100'],
        'kategori_id'  => ['required'],
    ]);

    $buku = Buku::findOrFail($id);
    $buku->update($validated);

    return redirect()->route('dashboard')->with('success', 'Data buku berhasil diperbarui!');
}


public function destroy($id)
{
    // Cari buku berdasarkan ID
    $buku = \App\Models\Buku::find($id);
    
    // Jika bukunya ada, hapus dari database
    if ($buku) {
        $buku->delete();
        
        // Redirect ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Buku berhasil dihapus!');
    }
    
    // Jika tidak ditemukan, kembalikan ke dashboard dengan pesan error
    return redirect()->route('dashboard')->with('error', 'Buku dengan ID tersebut tidak ditemukan.');
}

// public function destroy($id)
// {
//     // Cari buku berdasarkan ID
//     $buku = \App\Models\Buku::find($id);
    
//     // Jika bukunya ada, hapus dari database
//     if ($buku) {
//         $buku->delete();
//         return "<h1 style='color: deeppink; text-align: center; margin-top: 50px;'>HIHUUU</h1>";
//     }
    
//     return "<h3 style='color: red; text-align: center;'>Buku dengan ID tersebut tidak ditemukan.</h3>";
// }

    public function cari(Request $request)
{
    $judul = $request->query('judul');
    // $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'"); 
    $hasil = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]); 
    return response()->json($hasil);
}

public function cetakPdf()
{
    $peminjaman = \App\Models\Peminjaman::with(['user', 'buku'])->get();

    // ⚠️ Pastikan baris ini ada agar variabel $pdf terbentuk
    $pdf = app('dompdf.wrapper')->loadView('dashboard.laporan-pdf', compact('peminjaman'));

    return $pdf->download('laporan-peminjaman.pdf');
}


}
