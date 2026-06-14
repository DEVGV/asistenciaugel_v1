<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trabajador\StoreTrabajadorRequest;
use App\Models\Trabajador;
use App\Services\Trabajador\TrabajadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrabajadorController extends Controller
{
    public function __construct(
        private TrabajadorService $trabajadorService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('trabajador/Index', [
            'trabajadores' => $this->trabajadorService->listarPaginado($request),
            'filters' => $request->only(['search']),
            'perfiles' => $this->trabajadorService->listarPerfilesActivos(),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json(
            $this->trabajadorService->buscarParaAsignacion($request)
        );
    }

    public function store(StoreTrabajadorRequest $request): RedirectResponse
    {
        $this->trabajadorService->crearMasivo($request->toDTOs());

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajadores registrados exitosamente.');
    }

    public function show(Trabajador $trabajador): Response
    {
        return Inertia::render('trabajador/Show', [
            'trabajador' => $this->trabajadorService->obtenerConRelaciones($trabajador),
        ]);
    }

    public function destroy(Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->eliminar($trabajador);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador desactivado exitosamente.');
    }
}
