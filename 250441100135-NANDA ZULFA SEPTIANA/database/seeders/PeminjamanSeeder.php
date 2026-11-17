<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\User; // <-- 1. Tambahkan import User di sini
use App\Models\Buku; // <-- 1. Tambahkan import Buku di sini

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Cek apakah ada user, jika belum ada, buat user dummy otomatis secara instan
    $user = User::inRandomOrder()->first();
    if (!$user) {
        $user = User::factory()->create();
    }

    $buku = Buku::inRandomOrder()->first();
    if (!$buku) {
        return; // Mencegah error jika buku juga kosong
    }

    Peminjaman::create([
        'user_id' => $user->id,
        'buku_id' => $buku->id,
        'tanggal_pinjam' => now(),
        'status' => 'dipinjam',
    ]);
}
}