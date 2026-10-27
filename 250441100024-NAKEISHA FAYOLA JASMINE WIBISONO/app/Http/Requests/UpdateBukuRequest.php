<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn($rule) => array_merge(['sometimes'], $rule))
            ->all();
    }
}
