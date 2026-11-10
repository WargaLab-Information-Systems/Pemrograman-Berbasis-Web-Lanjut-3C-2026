<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        // Tampilan pagination sederhana yang cocok dengan CSS proyek (tanpa Tailwind).
        Paginator::defaultView('pagination.simple');
        Paginator::defaultSimpleView('pagination.simple');

        // Aturan pengelolaan buku dipusatkan di sini: hanya admin dan petugas.
        Gate::define('kelola-buku', fn (User $user) => $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_PETUGAS]));
    }
}
