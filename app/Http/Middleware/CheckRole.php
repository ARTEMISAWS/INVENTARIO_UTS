<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verifica si el usuario está autenticado
        // 2. Verifica si el rol del usuario NO es el que se requiere
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Si no tiene el rol, lo redirige o muestra un error 403 (Acceso Prohibido)
            abort(403, 'ACCESO NO AUTORIZADO');
        }

        // Si el usuario tiene el rol correcto, la petición continúa.
        return $next($request);
    }
}
