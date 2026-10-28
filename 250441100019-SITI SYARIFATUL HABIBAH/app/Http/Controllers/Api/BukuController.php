<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BukuController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $bukus = Buku::query()
            ->with('kategori')
            ->withCount('peminjamans')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->query('cari');
                $q->where(function ($w) use ($cari) {
                    $w->where('judul', 'like', "%{$cari}%")
                      ->orWhere('penulis', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->query('kategori')))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_terbit', $request->query('tahun')))
            ->latest()
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return BukuResource::collection($bukus)
            ->additional(['message' => 'Daftar buku berhasil diambil']);
    }

        public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->aturan(), $this->pesan(), $this->atribut());

        $buku = Buku::create($data);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $buku,
        ], 201);
    }

    public function show(Buku $buku): JsonResponse
    {
        $buku->load(['kategori', 'peminjamans.anggota']);

        return response()->json([
            'message' => 'Detail buku berhasil diambil',
            'data'    => $buku,
        ], 200);
    }

    public function update(Request $request, Buku $buku): JsonResponse
    {
        $data = $request->validate($this->aturan(true), $this->pesan(), $this->atribut());

        $buku->update($data);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => $buku->fresh('kategori'),
        ], 200);
    }

    public function destroy(Buku $buku): JsonResponse
    {
        if ($buku->peminjamans()->exists()) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman',
            ], 409);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }

    private function aturan(bool $sebagian = false): array
    {
        $aturan = [
            'kategori_id'  => ['bail', 'required', 'exists:kategoris,id'],
            'judul'        => ['bail', 'required', 'string', 'min:3', 'max:150'],
            'penulis'      => ['bail', 'required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['bail', 'required', 'integer', 'between:1900,' . date('Y')],
        ];

        if ($sebagian) {
            foreach ($aturan as $kolom => $rule) {
                $aturan[$kolom] = array_merge(['sometimes'], $rule);
            }
        }

        return $aturan;
    }

    private function pesan(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib diisi.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',

            'judul.required' => 'Judul buku wajib diisi.',
            'judul.string'   => 'Judul buku harus berupa teks.',
            'judul.min'      => 'Judul buku minimal :min karakter',
            'judul.max'      => 'Judul buku maksimal :max karakter.',

            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.string'   => 'Penulis harus berupa teks.',
            'penulis.min'      => 'Penulis minimal :min karakter.',
            'penulis.max'      => 'Penulis maksimal :max karakter.',

            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between'  => 'Tahun terbit harus antara :min sampai :max',
        ];
    }

    private function atribut(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'judul'        => 'judul',
            'penulis'      => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}