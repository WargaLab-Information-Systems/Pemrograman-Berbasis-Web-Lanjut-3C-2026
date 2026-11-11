<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // ID kategori digunakan untuk proses ubah data
            'kategori_id' => $this->kategori_id,

            // Nama atribut API dibuat berbeda dari kolom database
            'kategori' => $this->kategori?->nama,
            'judul_buku' => $this->judul,
            'penulis' => $this->penulis,
            'tahun' => $this->tahun_terbit,

            // Tetap disertakan untuk mendukung tampilan web lama
            'image' => $this->image,
            'description' => $this->description,
        ];
    }
}