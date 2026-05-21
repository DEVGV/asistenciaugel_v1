<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracion\StoreCondicionLaboralRequest;
use App\Http\Requests\Configuracion\UpdateCondicionLaboralRequest;
use App\Models\CondicionesLaborales;
use App\Services\Configuracion\CondicionLaboralService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CondicionLaboralController extends Controller
{
    public function __construct(
        private CondicionLaboralService $condicionLaboralService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('configuracion/condiciones-laborales/Index', [
            'condiciones' => $this->condicionLaboralService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreCondicionLaboralRequest $request): RedirectResponse
    {
        $this->condicionLaboralService->crear($request->toDTO());

        return redirect()->route('condiciones-laborales.index')
            ->with('success', 'Condición laboral registrada exitosamente.');
    }

    public function update(UpdateCondicionLaboralRequest $request, CondicionesLaborales $condicionLaboral): RedirectResponse
    {
        $this->condicionLaboralService->actualizar($condicionLaboral, $request->toDTO());

        return redirect()->route('condiciones-laborales.index')
            ->with('success', 'Condición laboral actualizada exitosamente.');
    }

    public function destroy(CondicionesLaborales $condicionLaboral): RedirectResponse
    {
        $this->condicionLaboralService->eliminar($condicionLaboral);

        return redirect()->route('condiciones-laborales.index')
            ->with('success', 'Condición laboral eliminada exitosamente.');
    }
}
