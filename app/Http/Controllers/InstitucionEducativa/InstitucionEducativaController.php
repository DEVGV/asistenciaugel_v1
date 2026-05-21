<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreInstEducRequest;
use App\Http\Requests\InstitucionEducativa\UpdateInstEducRequest;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\InstitucionEducativaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstitucionEducativaController extends Controller
{
    public function __construct(
        private InstitucionEducativaService $ieService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('institucion-educativa/Index', [
            'instituciones' => $this->ieService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(StoreInstEducRequest $request): RedirectResponse
    {
        $this->ieService->crear($request->toDTO());

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución educativa registrada exitosamente.');
    }

    public function show(InstitucionesEduc $institucione): Response
    {
        return Inertia::render('institucion-educativa/Show', [
            'institucion' => $this->ieService->obtenerConRelaciones($institucione),
        ]);
    }

    public function update(UpdateInstEducRequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->ieService->actualizar($institucione, $request->toDTO());

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución educativa actualizada exitosamente.');
    }

    public function destroy(InstitucionesEduc $institucione): RedirectResponse
    {
        $this->ieService->eliminar($institucione);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución educativa eliminada exitosamente.');
    }
}
