<?php

namespace App\Http\Middleware;

use App\Services\Auth\ContextoService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario autenticado tenga al menos uno de los permisos
 * indicados para la IE del contexto activo.
 *
 * Uso en rutas:
 *   ->middleware('permiso:trabajadores.ver')
 *   ->middleware('permiso:trabajadores.crear,trabajadores.editar')  // OR lógico
 */
class CheckPermiso
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    public function handle(Request $request, Closure $next, string ...$permisos): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'No autenticado.');
        }

        // Sin permisos requeridos → pasar (solo se usa auth + contexto)
        if (empty($permisos)) {
            return $next($request);
        }

        $ieId = $this->contextoService->ieId();

        if ($user->tieneAlgunPermiso($permisos, $ieId)) {
            return $next($request);
        }

        // Para requests Inertia, devolver redirect con mensaje
        if ($request->header('X-Inertia')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        abort(403, 'No tienes permiso para realizar esta acción.');
    }
}
