<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Solo el rol Administrador puede entrar a la administración. */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->hasRole('admin'), 403, 'Solo el administrador puede acceder a esta sección.');

        return $next($request);
    }
}
