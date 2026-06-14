<?php

namespace App\Http\Controllers\Entidad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Entidad\StoreEntidadRequest;
use App\Http\Requests\Entidad\UpdateEntidadRequest;
use App\Models\Entidades;
use App\Services\Entidad\EntidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EntidadController extends Controller
{
    public function __construct(
        private EntidadService $entidadService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('entidad/Index', [
            'entidades' => $this->entidadService->listarPaginado($request),
            'filters'   => $request->only(['search']),
        ]);
    }

    public function store(StoreEntidadRequest $request): RedirectResponse
    {
        $this->entidadService->crear($request->toDTO());

        return redirect()->route('entidades.index')
            ->with('success', 'Entidad registrada exitosamente.');
    }

    public function update(UpdateEntidadRequest $request, Entidades $entidade): RedirectResponse
    {
        $this->entidadService->actualizar($entidade, $request->toDTO());

        return redirect()->route('entidades.index')
            ->with('success', 'Entidad actualizada exitosamente.');
    }

    public function destroy(Entidades $entidade): RedirectResponse
    {
        $this->entidadService->eliminar($entidade);

        return redirect()->route('entidades.index')
            ->with('success', 'Entidad eliminada exitosamente.');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->entidadService->buscarParaSelect($request),
        ]);
    }
}
