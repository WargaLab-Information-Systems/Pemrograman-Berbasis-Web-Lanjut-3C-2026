<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportAccessDataSeeder extends Seeder
{
    public function run(): void
    {
        // Koneksi database lama dan Supabase.
        $source = DB::connection('mysql_local');
        $destination = DB::connection('pgsql');

        // Tabel yang akan diperiksa sebelum proses pemindahan.
        $tables = [
            'users',
            'roles',
            'permissions',
            'model_has_roles',
            'role_has_permissions',
            'model_has_permissions',
        ];

        // Pastikan seluruh tabel tujuan masih kosong.
        foreach ($tables as $table) {
            $count = $destination->table($table)->count();

            if ($count > 0) {
                throw new RuntimeException(
                    "Pemindahan dibatalkan. Tabel {$table} di Supabase tidak kosong."
                );
            }
        }

        // Seluruh proses pemindahan dilakukan dalam satu transaksi.
        $destination->transaction(function () use (
            $source,
            $destination,
            $tables
        ) {
            $results = [];

            // Urutan pemindahan agar relasi foreign key tetap aman.
            $orderedTables = [
                'users',
                'roles',
                'permissions',
                'role_has_permissions',
                'model_has_roles',
                'model_has_permissions',
            ];

            foreach ($orderedTables as $table) {
                $rows = $source->table($table)->get();

                $data = $rows
                    ->map(fn ($row) => (array) $row)
                    ->all();

                // Masukkan data jika tabel sumber berisi data.
                if (!empty($data)) {
                    foreach (array_chunk($data, 500) as $chunk) {
                        $destination->table($table)->insert($chunk);
                    }
                }

                $results[$table] = count($data);
            }

            // Sesuaikan sequence PostgreSQL setelah ID lama dipertahankan.
            foreach (['users', 'roles', 'permissions'] as $table) {
                $destination->select("
                    SELECT setval(
                        pg_get_serial_sequence('{$table}', 'id'),
                        COALESCE((SELECT MAX(id) FROM {$table}), 1),
                        EXISTS(SELECT 1 FROM {$table})
                    )
                ");
            }

            // Tampilkan ringkasan hasil pemindahan.
            foreach ($results as $table => $count) {
                $this->command->info(
                    "Tabel {$table}: {$count} data berhasil dipindahkan."
                );
            }
        });

        $this->command->info('Pemindahan akun dan hak akses selesai!');
    }
}
