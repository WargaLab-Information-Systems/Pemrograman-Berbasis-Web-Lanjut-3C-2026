<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Mendefinisikan Gate kelola-buku (hanya mengizinkan admin dan petugas)
        Gate::define('kelola-buku', function (User $user) {
            return method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('petugas'));
        });
    }
}
