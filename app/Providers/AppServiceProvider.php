<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        // ? Si es necesaria una logica de negocio exclusiva del modulo agregas un policy
        // Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);

        // Definir gates dinámicos para todos los permisos
        Gate::before(function ($user, $ability) {
            // Verificar si el usuario tiene el permiso específico
            return $user->roles()
                ->whereHas('permissions', function ($query) use ($ability) {
                    $query->where('permission_key', $ability);
                })
                ->exists();
        });
    }
}
