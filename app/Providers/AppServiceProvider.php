<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Pastikan ini di-import

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
        // Definisikan Gate untuk role Admin di sini, TANPA registerPolicies()
        Gate::define('is-pengelola', function ($admin) {
            return $admin->role === 'pengelola';
        });

        Gate::define('is-persetujuan', function ($admin) {
            return $admin->role === 'persetujuan';
        });
    }
}