<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'kategori_id'     => $this->kategori_id,                                    // dipakai form ubah
            'kategori'        => $this->whenLoaded('kategori', fn() => $this->kategori->nama), // kolom kategori_id -> atribut kategori (nama)
            'judul'           => $this->judul,
            'penulis'         => $this->penulis,
            'tahun'           => $this->tahun_terbit,                                   // kolom tahun_terbit -> atribut tahun
            'jumlah_dipinjam' => $this->whenCounted('peminjamans'),                     // peminjamans_count -> jumlah_dipinjam
            'dibuat_pada'     => $this->created_at?->format('d-m-Y H:i'),               // created_at -> dibuat_pada
            'riwayat_peminjaman' => $this->whenLoaded('peminjamans'),
        ];
    }
}
