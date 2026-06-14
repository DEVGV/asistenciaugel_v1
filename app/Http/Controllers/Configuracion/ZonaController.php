<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Zona\StoreZonaRequest;
use App\Http\Requests\Zona\UpdateZonaRequest;
use App\Models\Zonas;
use App\Services\Zona\ZonaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ZonaController extends Controller
{
    public function __construct(
        private ZonaService $zonaService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('configuracion/zonas/Index', [
            'zonas'   => $this->zonaService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreZonaRequest $request): RedirectResponse
    {
        $this->zonaService->crear($request->toDTO());

        return redirect()->route('zonas.index')
            ->with('success', 'Zona registrada exitosamente.');
    }

    public function update(UpdateZonaRequest $request, Zonas $zona): RedirectResponse
    {
        $this->zonaService->actualizar($zona, $request->toDTO());

        return redirect()->route('zonas.index')
            ->with('success', 'Zona actualizada exitosamente.');
    }

    public function destroy(Zonas $zona): RedirectResponse
    {
        $this->zonaService->eliminar($zona);

        return redirect()->route('zonas.index')
            ->with('success', 'Zona desactivada exitosamente.');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->zonaService->buscarParaSelect($request),
        ]);
    }
}
