<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    
    protected $table = 'buku';

    protected $fillable = [
        'kode_buku',
        'kategori_id',
        'judul',
        'penulis',
        'tahun_terbit',
        'gambar',
    ];

   
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

   
    public function getKategoriAttribute()
    {
        return $this->kategori()->first()->nama ?? 'Umum';
    }

    public function peminjaman()
    {
    return $this->hasMany(Peminjaman::class); // atau sesuaikan dengan nama model relasinya
    }
}