<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracion\StoreAreaRequest;
use App\Http\Requests\Configuracion\UpdateAreaRequest;
use App\Models\Areas;
use App\Services\Configuracion\AreaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AreaController extends Controller
{
    public function __construct(
        private AreaService $areaService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('configuracion/areas/Index', [
            'areas' => $this->areaService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreAreaRequest $request): RedirectResponse
    {
        $this->areaService->crear($request->toDTO());

        return redirect()->route('areas.index')
            ->with('success', 'Área registrada exitosamente.');
    }

    public function update(UpdateAreaRequest $request, Areas $area): RedirectResponse
    {
        $this->areaService->actualizar($area, $request->toDTO());

        return redirect()->route('areas.index')
            ->with('success', 'Área actualizada exitosamente.');
    }

    public function destroy(Areas $area): RedirectResponse
    {
        $this->areaService->eliminar($area);

        return redirect()->route('areas.index')
            ->with('success', 'Área desactivada exitosamente.');
    }
}
