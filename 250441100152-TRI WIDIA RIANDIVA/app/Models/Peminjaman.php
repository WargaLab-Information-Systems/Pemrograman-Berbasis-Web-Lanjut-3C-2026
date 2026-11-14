<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id', // Sesuaikan dengan kolom database phpMyAdmin
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    // Relasi ke Model User menggunakan foreign key anggota_id
    public function user()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    // Relasi ke Model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}