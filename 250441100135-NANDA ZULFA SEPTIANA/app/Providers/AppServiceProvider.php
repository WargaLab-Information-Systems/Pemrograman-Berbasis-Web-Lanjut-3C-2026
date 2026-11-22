<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\Facades\URL; 
use App\Models\Peminjaman;           
use App\Policies\PeminjamanPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
    {
        // Pastikan baris ini ada di dalam function boot()
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);
        // Paksa semua URL menggunakan HTTPS di Vercel
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

}
