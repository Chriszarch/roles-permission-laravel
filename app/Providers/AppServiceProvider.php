<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::define('access-module', function ($user, $moduleName) {
            return $user->hasAccessToModule($moduleName);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Schema::defaultStringLength(191);
       
       // Registrar Policy
       Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
    }
}
