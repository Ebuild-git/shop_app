<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifiez si l'utilisateur est authentifié
        if ($request->user() && $request->user()->role == 'admin') {
            return $next($request);
        }

        // Redirigez l'utilisateur vers une page d'erreur ou une autre action
        abort(403, 'Unauthorized');
    }
}
