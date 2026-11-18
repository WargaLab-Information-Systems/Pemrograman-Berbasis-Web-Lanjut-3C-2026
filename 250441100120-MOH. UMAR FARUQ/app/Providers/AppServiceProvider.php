<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

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
    // Definisi Gate kelola-buku untuk Admin dan Petugas (Soal 3)
    Gate::define('kelola-buku', function (User $user) {
        return $user->hasRole(['admin', 'petugas']);
    });
}
}
