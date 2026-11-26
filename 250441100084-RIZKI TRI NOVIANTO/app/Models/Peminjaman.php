<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    // Relasi anggota_id merujuk ke model User
    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    // Relasi ke model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}