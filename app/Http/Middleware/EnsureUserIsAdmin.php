<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Middleware para asegurar que el usuario autenticado tenga el rol de administrador
class EnsureUserIsAdmin
{
    /**
     * function handle: Verifica si el usuario autenticado tiene el rol de administrador antes de permitir el acceso a la
     * ruta protegida.
     * @param Request $request: La solicitud HTTP entrante que contiene la información del usuario autenticado.
     * @param Closure $next: La función de callback que representa la siguiente etapa del procesamiento
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario autenticado existe y tiene el rol de administrador utilizando el método isAdmin() del modelo User
        if (! $request->user() || ! $request->user()->isAdmin()) {
            // Devolver una respuesta JSON con un mensaje de error y un código de estado 403 Forbidden si el usuario no tiene permisos para acceder a la ruta protegida
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción.',
            ], 403);
        }
        // Continuar con la siguiente etapa del procesamiento de la solicitud si el usuario es un administrador
        return $next($request);
    }
}