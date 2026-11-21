<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
    'program_studi_id',
    'kode_mk',
    'nama_mk',
    'sks',
    'status_aktif',
    'tanggal_dibuka',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'tanggal_dibuka' => 'datetime',
        ];
    }
}