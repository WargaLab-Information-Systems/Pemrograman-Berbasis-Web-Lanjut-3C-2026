<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Peminjaman;
use App\Policies\PeminjamanPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mendefinisikan Gate kelola-buku (Soal 3)
        Gate::define('kelola-buku', function (User $user) {
            return method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('petugas'));
        });

        // Mendaftarkan PeminjamanPolicy untuk menutup celah IDOR A4 (Soal 4)
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
    }
}