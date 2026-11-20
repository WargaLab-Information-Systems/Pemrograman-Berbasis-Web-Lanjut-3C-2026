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
            'kategori_id' => $this->kategori_id,
            'kategori' => $this->kategori->nama ?? $this->kategori->kategori ?? '-',
            'judul_buku' => $this->judul,
            'nama_penulis' => $this->penulis,
            'tahun_rilis' => $this->tahun_terbit,
        ];
    }
}
