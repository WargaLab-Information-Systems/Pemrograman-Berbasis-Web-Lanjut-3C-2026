<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;  
use App\Models\Peminjaman; 

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];

    public function getTahunAttribute()
    {
        return $this->attributes['tahun_terbit'] ?? null;
    }

    // Relasi ke tabel Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

}