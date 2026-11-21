<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataKuliah as MataKuliahModel;

class MataKuliah extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataKuliahModel::factory()->count(10)->create();
    }
}
