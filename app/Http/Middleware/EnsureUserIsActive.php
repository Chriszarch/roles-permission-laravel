<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // Read the latest is_active straight from DB to avoid stale cache
            $isActive = $user->newQuery()->whereKey($user->getKey())->value('is_active');

            if (! $isActive) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Account inactive'], 401);
                }

                return redirect()->route('login')->withErrors([
                    'email' => 'Tu cuenta está inactiva.',
                ]);
            }
        }

        return $next($request);
    }
}
