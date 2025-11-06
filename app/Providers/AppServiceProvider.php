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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // TODO revisar porque no me parece una buena practica
        // Desactivar autodescubrimiento de policies para evitar errores
        // La autorización se maneja completamente con Gates
        \Illuminate\Support\Facades\Gate::guessPolicyNamesUsing(function () {
            return null;
        });

        // Registrar Policy
        // ? Si es necesaria una logica de negocio exclusiva del modulo agregas un policy
        // Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);

        // Definir gates dinámicos para todos los permisos con jerarquía
        Gate::before(function ($user, $ability) {
            // JERARQUÍA 1: Verificar permisos directos del usuario (PRIORIDAD MÁXIMA)
            $hasDirectPermission = $user->directPermissions()
                ->where('permission_key', $ability)
                ->exists();

            if ($hasDirectPermission) {
                return true;
            }

            // JERARQUÍA 2: Verificar permisos a través de roles
            $hasRolePermission = $user->roles()
                ->whereHas('permissions', function ($query) use ($ability) {
                    $query->where('permission_key', $ability);
                })
                ->exists();

            if ($hasRolePermission) {
                return true;
            }

            // No denegar explícitamente para permitir que las policies manejen el resto
            return null;
        });

        // Gate para verificar acceso a módulos completos
        Gate::define('access-module', function ($user, $moduleName) {
            // Verificar si el usuario tiene algún permiso del módulo
            // Primero verificar permisos directos
            $hasDirectModulePermission = $user->directPermissions()
                ->whereHas('module', function ($query) use ($moduleName) {
                    $query->where('name', $moduleName);
                })
                ->exists();

            if ($hasDirectModulePermission) {
                return true;
            }

            // Luego verificar permisos por roles
            return $user->roles()
                ->whereHas('permissions.module', function ($query) use ($moduleName) {
                    $query->where('name', $moduleName);
                })
                ->exists();
        });
    }
}
