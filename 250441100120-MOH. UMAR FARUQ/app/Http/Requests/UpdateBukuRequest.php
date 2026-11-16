<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $field => $rule) {
            $rules[$field] = 'sometimes|' . $rule;
        }

        return $rules;
    }
}
