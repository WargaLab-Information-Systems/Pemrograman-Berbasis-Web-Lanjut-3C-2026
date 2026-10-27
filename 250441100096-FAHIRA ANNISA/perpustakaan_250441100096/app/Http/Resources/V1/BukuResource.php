<?php

namespace App\Http\Resources\V1;

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
        'judul' => $this->judul,       // Pemetaan atribut berbeda dari kolom DB
        'penulis' => $this->penulis,
        'tahun_terbit' => $this->tahun_terbit,
        ];
    }
}
