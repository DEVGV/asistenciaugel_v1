<?php

namespace App\Http\Middleware;

use App\Services\Auth\ContextoService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Garantiza que el usuario autenticado tenga un contexto de trabajo (UGEL/IE).
 * - Una sola opción → se establece automáticamente.
 * - Varias opciones → redirige a la pantalla de selección.
 * - Sin asignaciones → redirige a la pantalla "sin acceso".
 */
class EnsureContextoSeleccionado
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $this->contextoService->establecido()) {
            return $next($request);
        }

        // No interceptar estas rutas especiales
        if ($request->routeIs('contexto.*', 'logout', 'sin-acceso')) {
            return $next($request);
        }

        if ($this->contextoService->establecerAutomatico($user)) {
            return $next($request);
        }

        $opciones = $this->contextoService->opcionesParaUsuario($user);

        // Sin asignaciones → el usuario no tiene acceso a ninguna IE/UGEL
        if ($this->contextoService->totalOpciones($opciones) === 0) {
            return redirect()->route('sin-acceso');
        }

        return redirect()->guest(route('contexto.seleccionar'));
    }
}
