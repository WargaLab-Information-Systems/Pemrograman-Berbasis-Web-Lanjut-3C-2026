<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     */
    public function rules(): array
    {
        $tahunSaatIni = date ('Y');

        return [
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => "required|integer|between:1900,{$tahunSaatIni}",
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa angka teks.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maximal :max karakter.',
            'between' => ':attribute harus antara :min sampai :max.',
            'exists' => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_id' => 'kategori',
            'judul' => 'judul_buku',
            'penulis' => 'penulis',
            'tahun_terbit'  => 'tahun_terbit',
        ];
    }
}
