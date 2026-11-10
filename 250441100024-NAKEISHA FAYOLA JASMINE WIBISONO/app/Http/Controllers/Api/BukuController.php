<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $buku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->cari;
                $q->where(function ($w) use ($cari) {
                    $w->where('judul', 'like', "%{$cari}%")
                        ->orWhere('penulis', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->filled('tahun'), fn($q) => $q->where('tahun_terbit', $request->tahun))
            ->latest()
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Otomatis berisi: data, links, meta (pagination)
        return BukuResource::collection($buku);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        $buku = Buku::create($data);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => new BukuResource($buku->load('kategori')),
        ], 201);
    }

    public function show(string $id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json(['data' => new BukuResource($buku)], 200);
    }

    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        // 'sometimes' supaya update sebagian (hanya kirim judul) tetap lolos
        $rules = collect($this->rules())
            ->map(fn($rule) => array_merge(['sometimes'], $rule))
            ->all();

        $data = $request->validate($rules, $this->messages(), $this->attributes());

        $buku->update($data);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => new BukuResource($buku->load('kategori')),
        ], 200);
    }

    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        try {
            $buku->delete();
        } catch (QueryException $e) {
            // Terjadi kalau buku masih punya data peminjaman (foreign key)
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena masih memiliki data peminjaman.',
            ], 409);
        }

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }

    private function rules(): array
    {
        return [
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'min:3', 'max:150'],
            'penulis'      => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
        ];
    }

    private function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib diisi.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',

            'judul.required' => 'Judul buku wajib diisi.',
            'judul.string'   => 'Judul buku harus berupa teks.',
            'judul.min'      => 'Judul buku minimal 3 karakter.',
            'judul.max'      => 'Judul buku maksimal 150 karakter.',

            'penulis.required' => 'Penulis wajib diisi.',
            'penulis.string'   => 'Penulis harus berupa teks.',
            'penulis.min'      => 'Penulis minimal 3 karakter.',
            'penulis.max'      => 'Penulis maksimal 100 karakter.',

            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between'  => 'Tahun terbit harus antara 1900 sampai ' . date('Y') . '.',
        ];
    }

    private function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'judul'        => 'judul',
            'penulis'      => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}
