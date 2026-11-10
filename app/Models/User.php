<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

// Catatan: 'role' sengaja TIDAK ada di $fillable. Role hanya bisa ditetapkan
// lewat assignRole()/syncRoles() oleh kode yang berwenang (seeder, admin).
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PETUGAS = 'petugas';
    public const ROLE_SISWA = 'siswa';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Data anggota milik user ini; dibuat otomatis bila belum ada
     * (dibutuhkan agar user dapat meminjam buku).
     */
    public function ensureAnggota(): Anggota
    {
        return $this->anggota()->firstOrCreate([], [
            'nama' => $this->name,
            'email' => $this->email,
            'no_telepon' => '-',
        ]);
    }

}
