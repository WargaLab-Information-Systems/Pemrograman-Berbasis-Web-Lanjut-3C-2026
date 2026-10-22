<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus'; 
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'tahun_terbit']; 
    
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id'); 
    }
}
