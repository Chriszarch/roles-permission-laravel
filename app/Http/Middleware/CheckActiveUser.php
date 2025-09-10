<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está logueado
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si el usuario ya no está activo, cerrar sesión
            if (!$user->is_active) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Tu sesión expiró.');
            }
        }

        return $next($request);
    }
}
