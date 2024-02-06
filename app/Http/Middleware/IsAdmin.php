<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado e é um administrador
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // Se não for um administrador, pode retornar um erro ou redirecionar
        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
