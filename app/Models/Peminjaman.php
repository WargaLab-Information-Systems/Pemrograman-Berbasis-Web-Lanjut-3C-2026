<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans'; // sesuaikan jika nama tabel Anda berbeda (misal: peminjaman)

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian_aktual',
        'status',
    ];

    /**
     * Relasi ke model User (sebagai peminjam/anggota)
     */
    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    /**
     * Relasi ke model Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}