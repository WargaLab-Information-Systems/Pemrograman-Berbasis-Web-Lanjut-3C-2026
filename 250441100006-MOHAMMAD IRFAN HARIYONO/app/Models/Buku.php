<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];
    protected $appends = ['sedang_dipinjam'];

    public function getSedangDipinjamAttribute()
    {
        return \App\Models\Peminjaman::where('buku_id', $this->id)
            ->whereIn('status', ['Menunggu Persetujuan', 'Sedang Dipinjam'])
            ->exists();
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Tambahkan relasi ini untuk menghitung jumlah peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
