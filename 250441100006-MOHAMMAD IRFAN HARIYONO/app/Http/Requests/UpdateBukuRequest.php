<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    // Tidak perlu menulis ulang rules(), messages(), atau attributes() karena sudah diwarisi dari StoreBukuRequest
}