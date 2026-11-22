<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id'  => ['bail', 'required', 'exists:kategoris,id'],
            'judul'        => ['bail', 'required', 'string', 'min:3', 'max:150'],
            'penulis'      => ['bail', 'required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['bail', 'required', 'integer', 'between:1900,' . date('Y')],
        ];
    }

    public function messages(): array
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

    public function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'judul'        => 'judul',
            'penulis'      => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}