<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleActiveRole
{
    /**
     * Handle an incoming request.
     *
     * Este middleware gestiona el rol activo del usuario:
     * 1. Si no hay rol activo, establece el primer rol disponible
     * 2. Si el rol activo no es válido, lo resetea
     * 3. Asegura que el rol activo esté siempre disponible en la sesión
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if ($user) {
            $activeRole = Session::get('active_role');
            $availableRoles = $user->roles()
                ->where('is_active', true)
                ->orderByDesc('priority') // Ordenar por prioridad si existe
                ->pluck('name')
                ->toArray();

            // Si no hay roles disponibles, limpiar sesión y continuar
            if (empty($availableRoles)) {
                Session::forget(['active_role', 'user_permissions', 'user_roles']);
                return $next($request);
            }

            // Si no hay rol activo o el rol activo no es válido,
            // seleccionar el rol de mayor prioridad
            if (!$activeRole || !in_array($activeRole, $availableRoles)) {
                $activeRole = $availableRoles[0];
                
                // Actualizar la sesión con el nuevo rol activo
                Session::forget(['user_permissions', 'user_roles']);
                Session::put('active_role', $activeRole);
            }

            // Asegurar que los permisos en sesión correspondan al rol activo
            if (!Session::has('user_permissions')) {
                $permissions = $user->getAllPermissions($activeRole);
                Session::put('user_permissions', $permissions);
            }
        }

        return $next($request);
    }
}
