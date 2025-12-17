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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            abort(403, 'DEBE INICIAR SESIÓN');
        }

        // 2. Verificar si el rol del usuario está dentro de los roles permitidos
        // $roles ahora es un array: ['admin', 'superadmin']
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'ACCESO NO AUTORIZADO: No tienes los permisos necesarios.');
        }

        return $next($request);
    }
}
