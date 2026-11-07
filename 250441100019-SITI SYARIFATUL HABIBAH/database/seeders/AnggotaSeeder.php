<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Budi Santoso',   'budi.santoso@gmail.com',   '081234567801'],
            ['Siti Aminah',    'siti.aminah@gmail.com',    '081234567802'],
            ['Ahmad Fauzi',    'ahmad.fauzi@gmail.com',    '081234567803'],
            ['Rina Wulandari', 'rina.wulandari@gmail.com', '081234567804'],
            ['Eko Prasetyo',   'eko.prasetyo@gmail.com',   '081234567805'],
            ['Putri Maharani', 'putri.maharani@gmail.com', '081234567806'],
            ['Dimas Aditya',   'dimas.aditya@gmail.com',   '081234567807'],
            ['Nur Aisyah',     'nur.aisyah@gmail.com',     '081234567808'],
        ];

        foreach ($data as [$nama, $email, $noTelepon]) {
            Anggota::factory()->create([
                'nama'       => $nama,
                'email'      => $email,
                'no_telepon' => $noTelepon,
            ]);
        }
    }
}
