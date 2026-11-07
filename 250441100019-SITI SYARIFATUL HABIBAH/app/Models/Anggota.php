<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }

    // Akun login pemilik data anggota ini (boleh kosong untuk data lama)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
