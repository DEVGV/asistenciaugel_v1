<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trabajador\StoreTrabajadorRequest;
use App\Http\Requests\Trabajador\UpdateTrabajadorRequest;
use App\Models\Trabajador;
use App\Services\Persona\PersonaService;
use App\Services\Trabajador\TrabajadorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrabajadorController extends Controller
{
    public function __construct(
        private TrabajadorService $trabajadorService,
        private PersonaService $personaService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('trabajador/Index', [
            'trabajadores' => $this->trabajadorService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
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

    public function edit(Trabajador $trabajador): Response
    {
        return Inertia::render('trabajador/Edit', [
            'trabajador' => $trabajador->load(['persona.tipoDocIdentidad', 'persona.sexo']),
        ]);
    }

    public function update(UpdateTrabajadorRequest $request, Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->actualizar($trabajador, $request->toDTO());

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador actualizado exitosamente.');
    }

    public function destroy(Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->eliminar($trabajador);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador desactivado exitosamente.');
    }
}
