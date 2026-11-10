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
            'kategori_id' => [
                'required',
                'integer',
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
            ],
            'tahun_terbit' => [
                'required',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' =>
                'Kategori wajib diisi.',
            'kategori_id.integer' =>
                'Kategori harus berupa angka.',
            'kategori_id.exists' =>
                'Kategori yang dipilih tidak valid.',

            'judul.required' =>
                'Judul wajib diisi.',
            'judul.string' =>
                'Judul buku harus berupa teks.',
            'judul.min' =>
                'Judul buku minimal 3 karakter.',
            'judul.max' =>
                'Judul buku maksimal 150 karakter.',

            'penulis.required' =>
                'Penulis wajib diisi.',
            'penulis.string' =>
                'Penulis harus berupa teks.',
            'penulis.min' =>
                'Penulis minimal 3 karakter.',
            'penulis.max' =>
                'Penulis maksimal 100 karakter.',

            'tahun_terbit.required' =>
                'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' =>
                'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.min' =>
                'Tahun terbit harus antara 1900 sampai ' .
                date('Y') . '.',
            'tahun_terbit.max' =>
                'Tahun terbit harus antara 1900 sampai ' .
                date('Y') . '.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_id' => 'kategori',
            'judul' => 'judul buku',
            'penulis' => 'penulis',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}
