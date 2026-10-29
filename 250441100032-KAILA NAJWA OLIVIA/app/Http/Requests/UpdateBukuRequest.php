<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        return [
            'kategori_id' => 'sometimes|required|integer|exists:kategoris,id',
            'judul' => 'sometimes|required|string|min:3|max:150',
            'penulis' => 'sometimes|required|string|min:3|max:100',
            'tahun_terbit' => 'sometimes|required|integer|between:1900,' . date('Y'),
        ];
    }
}
