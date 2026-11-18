<?php

namespace Database\Seeders;

use App\Models\ProgramStudi as ProgramStudiModel;
use Illuminate\Database\Seeder;

class ProgramStudi extends Seeder
{
    public function run(): void
    {
        ProgramStudiModel::updateOrCreate(
            ['kode' => 'SI'],
            ['nama' => 'Sistem Informasi']
        );

        ProgramStudiModel::updateOrCreate(
            ['kode' => 'TI'],
            ['nama' => 'Teknik Informatika']
        );

        ProgramStudiModel::updateOrCreate(
            ['kode' => 'MI'],
            ['nama' => 'Manajemen Informatika']
        );
    }
}