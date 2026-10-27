<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
        'kategori_id'  => ($isUpdate ? 'sometimes' : 'required') . '|exists:kategoris,id',
        'judul'        => ($isUpdate ? 'sometimes' : 'required') . '|string|min:3|max:150',
        'penulis'      => ($isUpdate ? 'sometimes' : 'required') . '|string|min:3|max:100',
        'tahun_terbit' => ($isUpdate ? 'sometimes' : 'required') . '|integer|between:1900,' . date('Y'),
        'cover'        => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'integer'  => ':attribute harus berupa angka.',
            'min'      => ':attribute minimal :min karakter.',
            'max'      => ':attribute maksimal :max karakter.',
            'between'  => ':attribute harus antara :min sampai :max.',
            'exists'   => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_id'  => 'kategori',
            'tahun_terbit' => 'tahun terbit',
            'judul'        => 'Judul buku',
            'penulis'      => 'penulis',
        ];
    }
}
