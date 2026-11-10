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
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $currentYear = date('Y');

        return [
            'kategori_id'  => [$isUpdate ? 'sometimes' : 'required', 'required_with:kategori_id', 'exists:kategoris,id'],
            'judul'        => [$isUpdate ? 'sometimes' : 'required', 'required_with:judul', 'string', 'min:3', 'max:150'],
            'penulis'      => [$isUpdate ? 'sometimes' : 'required', 'required_with:penulis', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => [$isUpdate ? 'sometimes' : 'required', 'required_with:tahun_terbit', 'integer', "between:1900,{$currentYear}"],
            'gambar'       => [$isUpdate ? 'sometimes' : 'required', 'required_with:gambar', 'string'],
        ];
    }

    public function messages(): array
    {
        $currentYear = date('Y');
        return [
            'kategori_id.required'  => 'Kategori wajib diisi.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak valid.',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.string'          => 'Judul buku harus berupa teks.',
            'judul.min'             => 'Judul buku minimal 3 karakter.',
            'judul.max'             => 'Judul buku maksimal 150 karakter.',
            'penulis.required'      => 'Penulis wajib diisi.',
            'penulis.string'        => 'Penulis harus berupa teks.',
            'penulis.min'           => 'Penulis minimal 3 karakter.',
            'penulis.max'           => 'Penulis maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa bilangan bulat.',
            'tahun_terbit.between'  => "Tahun terbit harus antara 1900 sampai {$currentYear}.",
            'gambar.required'       => 'Gambar wajib diisi.',
            'gambar.string'         => 'Gambar harus berupa teks nama file.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'tahun_terbit' => 'tahun terbit',
        ];
    }
}