<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BukuController extends Controller
{
    // =========================================================
    // MENAMPILKAN DAFTAR BUKU
    // =========================================================
    public function index(Request $request)
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        // Pencarian berdasarkan judul atau penulis
        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', '%' . $cari . '%')
                    ->orWhere('penulis', 'like', '%' . $cari . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where(
                'kategori_id',
                $request->kategori
            );
        }

        // Filter berdasarkan tahun terbit
        if ($request->filled('tahun')) {
            $query->where(
                'tahun_terbit',
                $request->tahun
            );
        }

        // Pagination 10 buku per halaman
        $bukus = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return BukuResource::collection($bukus);
    }


    // =========================================================
    // MENAMBAHKAN BUKU BARU
    // =========================================================
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'kategori_id' => [
                    'required',
                    'exists:kategoris,id',
                ],

                'judul' => [
                    'required',
                    'string',
                    'min:3',
                    'max:150',
                ],

                'penulis' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[\p{L}\s.\'-]+$/u',
                ],

                'tahun_terbit' => [
                    'required',
                    'integer',
                    'between:1900,' . date('Y'),
                ],

                // Cover dan deskripsi tidak wajib
                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png',
                    'max:2048',
                ],

                'description' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                // Validasi kategori
                'kategori_id.required' =>
                    'Kategori buku wajib dipilih.',

                'kategori_id.exists' =>
                    'Kategori buku yang dipilih tidak valid.',


                // Validasi judul
                'judul.required' =>
                    'Judul buku wajib diisi.',

                'judul.string' =>
                    'Judul buku harus berupa teks.',

                'judul.min' =>
                    'Judul buku minimal harus 3 karakter.',

                'judul.max' =>
                    'Judul buku maksimal 150 karakter.',


                // Validasi penulis
                'penulis.required' =>
                    'Nama penulis wajib diisi.',

                'penulis.string' =>
                    'Nama penulis harus berupa teks.',

                'penulis.min' =>
                    'Nama penulis minimal harus 3 karakter.',

                'penulis.max' =>
                    'Nama penulis maksimal 100 karakter.',

                'penulis.regex' =>
                    'Nama penulis tidak boleh mengandung angka.',


                // Validasi tahun terbit
                'tahun_terbit.required' =>
                    'Tahun terbit wajib diisi.',

                'tahun_terbit.integer' =>
                    'Tahun terbit harus berupa angka.',

                'tahun_terbit.between' =>
                    'Tahun terbit harus berada antara 1900 sampai ' .
                    date('Y') . '.',


                // Validasi cover
                'image.image' =>
                    'Cover buku harus berupa gambar.',

                'image.mimes' =>
                    'Cover buku harus berformat JPG, JPEG, atau PNG.',

                'image.max' =>
                    'Ukuran cover buku maksimal 2 MB.',


                // Validasi deskripsi
                'description.string' =>
                    'Deskripsi buku harus berupa teks.',
            ]
        );


        // Jika user mengunggah cover buku
        if ($request->hasFile('image')) {

            $file = $request->file('image');


            // Membuat nama file yang unik
            $namaFile =
                time() . '_' .
                uniqid() . '.' .
                $file->getClientOriginalExtension();


            // Menyimpan cover ke public/images/books
            $file->move(
                public_path('images/books'),
                $namaFile
            );


            // Database hanya menyimpan nama file
            $validated['image'] = $namaFile;
        }


        // Menyimpan data buku
        $buku = Buku::create($validated);


        // Mengembalikan data melalui API Resource
        return (new BukuResource(
            $buku->load('kategori')
        ))
            ->response()
            ->setStatusCode(201);
    }


    // =========================================================
    // MENAMPILKAN DETAIL SATU BUKU
    // =========================================================
    public function show(string $id)
    {
        $buku = Buku::with('kategori')->find($id);


        if (!$buku) {

            return response()->json(
                [
                    'message' =>
                        'Buku tidak ditemukan.',
                ],
                404
            );
        }


        return new BukuResource($buku);
    }


    // =========================================================
    // MEMPERBARUI DATA BUKU
    // =========================================================
    public function update(
        Request $request,
        string $id
    ) {
        $buku = Buku::find($id);


        if (!$buku) {

            return response()->json(
                [
                    'message' =>
                        'Buku tidak ditemukan.',
                ],
                404
            );
        }


        $validated = $request->validate(
            [
                'kategori_id' => [
                    'required',
                    'exists:kategoris,id',
                ],

                'judul' => [
                    'required',
                    'string',
                    'min:3',
                    'max:150',
                ],

                'penulis' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[\p{L}\s.\'-]+$/u',
                ],

                'tahun_terbit' => [
                    'required',
                    'integer',
                    'between:1900,' . date('Y'),
                ],

                // Cover dan deskripsi tidak wajib
                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png',
                    'max:2048',
                ],

                'description' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                // Validasi kategori
                'kategori_id.required' =>
                    'Kategori buku wajib dipilih.',

                'kategori_id.exists' =>
                    'Kategori buku yang dipilih tidak valid.',


                // Validasi judul
                'judul.required' =>
                    'Judul buku wajib diisi.',

                'judul.string' =>
                    'Judul buku harus berupa teks.',

                'judul.min' =>
                    'Judul buku minimal harus 3 karakter.',

                'judul.max' =>
                    'Judul buku maksimal 150 karakter.',


                // Validasi penulis
                'penulis.required' =>
                    'Nama penulis wajib diisi.',

                'penulis.string' =>
                    'Nama penulis harus berupa teks.',

                'penulis.min' =>
                    'Nama penulis minimal harus 3 karakter.',

                'penulis.max' =>
                    'Nama penulis maksimal 100 karakter.',

                'penulis.regex' =>
                    'Nama penulis tidak boleh mengandung angka.',


                // Validasi tahun terbit
                'tahun_terbit.required' =>
                    'Tahun terbit wajib diisi.',

                'tahun_terbit.integer' =>
                    'Tahun terbit harus berupa angka.',

                'tahun_terbit.between' =>
                    'Tahun terbit harus berada antara 1900 sampai ' .
                    date('Y') . '.',


                // Validasi cover
                'image.image' =>
                    'Cover buku harus berupa gambar.',

                'image.mimes' =>
                    'Cover buku harus berformat JPG, JPEG, atau PNG.',

                'image.max' =>
                    'Ukuran cover buku maksimal 2 MB.',


                // Validasi deskripsi
                'description.string' =>
                    'Deskripsi buku harus berupa teks.',
            ]
        );


        // Jika user memilih cover baru
        if ($request->hasFile('image')) {

            $file = $request->file('image');


            // Membuat nama file baru yang unik
            $namaFile =
                time() . '_' .
                uniqid() . '.' .
                $file->getClientOriginalExtension();


            // Menyimpan cover baru
            $file->move(
                public_path('images/books'),
                $namaFile
            );


            // Menghapus cover lama jika file tersebut ada
            if (
                $buku->image &&
                File::exists(
                    public_path(
                        'images/books/' .
                        $buku->image
                    )
                )
            ) {

                File::delete(
                    public_path(
                        'images/books/' .
                        $buku->image
                    )
                );
            }


            // Menyimpan nama cover baru
            $validated['image'] = $namaFile;

        } else {

            /*
             * Jika tidak memilih cover baru,
             * cover lama tetap digunakan.
             */
            unset($validated['image']);
        }


        // Memperbarui data buku
        $buku->update($validated);


        // Mengembalikan data terbaru melalui API Resource
        return new BukuResource(
            $buku
                ->fresh()
                ->load('kategori')
        );
    }


    // =========================================================
    // MENGHAPUS BUKU
    // =========================================================
    public function destroy(string $id)
    {
        $buku = Buku::find($id);


        if (!$buku) {

            return response()->json(
                [
                    'message' =>
                        'Buku tidak ditemukan.',
                ],
                404
            );
        }


        $buku->delete();


        return response()->json(
            [
                'message' =>
                    'Buku berhasil dihapus.',
            ],
            200
        );
    }
}