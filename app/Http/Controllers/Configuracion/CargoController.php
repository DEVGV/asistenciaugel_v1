<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracion\StoreCargoRequest;
use App\Http\Requests\Configuracion\UpdateCargoRequest;
use App\Models\Cargos;
use App\Services\Configuracion\CargoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CargoController extends Controller
{
    public function __construct(
        private CargoService $cargoService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('configuracion/cargos/Index', [
            'cargos' => $this->cargoService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreCargoRequest $request): RedirectResponse
    {
        $this->cargoService->crear($request->toDTO());

        return redirect()->route('cargos.index')
            ->with('success', 'Cargo registrado exitosamente.');
    }

    public function update(UpdateCargoRequest $request, Cargos $cargo): RedirectResponse
    {
        $this->cargoService->actualizar($cargo, $request->toDTO());

        return redirect()->route('cargos.index')
            ->with('success', 'Cargo actualizado exitosamente.');
    }

    public function destroy(Cargos $cargo): RedirectResponse
    {
        $this->cargoService->eliminar($cargo);

        return redirect()->route('cargos.index')
            ->with('success', 'Cargo desactivado exitosamente.');
    }
}
