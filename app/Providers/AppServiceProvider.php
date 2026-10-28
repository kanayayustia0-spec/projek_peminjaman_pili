<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

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
        // Definisikan Gate untuk role Admin yang mendukung guard auth:admin
        Gate::define('is-pengelola', function ($user = null) {
            $admin = Auth::guard('admin')->user() ?? $user;
            return $admin instanceof Admin;
        });

        Gate::define('is-persetujuan', function ($user = null) {
            $admin = Auth::guard('admin')->user() ?? $user;
            return $admin instanceof Admin;
        });
    }
}