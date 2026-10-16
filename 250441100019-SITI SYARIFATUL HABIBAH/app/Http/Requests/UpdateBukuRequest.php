<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        // Mewarisi semua rule Store, ditambah 'sometimes' agar
        // update sebagian (hanya kirim judul) tetap lolos.
        return collect(parent::rules())
            ->map(fn (array $rule) => ['sometimes', ...$rule])
            ->all();
    }
}