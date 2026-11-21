<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    
    protected $table = 'peminjamans';
    
    // Jangan lupa tambahkan 'status' agar bisa disimpan ke database
    protected $fillable = ['anggota_id', 'buku_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    /**
     * Relasi ke tabel bukus
     * Artinya: 1 data peminjaman memiliki 1 buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Relasi ke tabel anggotas
     * Artinya: 1 data peminjaman dilakukan oleh 1 anggota
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}