<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tahunSekarang = date('Y');

        return [
            'kategori_id' => 'sometimes|nullable|exists:kategoris,id',
            'judul' => 'sometimes|string|min:3|max:150',
            'penulis' => 'sometimes|string|min:3|max:100',
            'tahun_terbit' => "sometimes|integer|between:1900,{$tahunSekarang}",
        ];
    }
}
