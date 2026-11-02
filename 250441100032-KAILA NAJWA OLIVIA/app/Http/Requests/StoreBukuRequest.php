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
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kode kategori wajib dipilih.',
            'kategori_id.integer' => 'Kode kategori harus berupa angka.',
            'kategori_id.exists' => 'Kode kategori tidak ditemukan.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'judul.max' => 'Judul buku maksimal 150 karakter.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'penulis.max' => 'Nama penulis maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka bulat.',
            'tahun_terbit.between' => 'Tahun terbit harus antara 1900 dan tahun sekarang.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_id' => 'kode kategori',
            'judul' => 'judul buku',
            'penulis' => 'nama penulis',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}
