<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Http\Requests\Persona\StorePersonaRequest;
use App\Http\Requests\Persona\UpdatePersonaRequest;
use App\Models\Personas;
use App\Services\Persona\PersonaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonaController extends Controller
{
    public function __construct(
        private PersonaService $personaService,
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('persona/Index', [
            'personas' => $this->personaService->listarPaginado($request),
            'filters' => $request->only(['search']),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json(
            $this->personaService->listarPaginado($request)
        );
    }

    public function show(Personas $persona): Response
    {
        return Inertia::render('persona/Index', [
            'personas' => $this->personaService->listarPaginado(request()),
            'filters' => request()->only(['search']),
            'selectedPersona' => $this->personaService->obtenerConRelaciones($persona),
        ]);
    }

    public function store(StorePersonaRequest $request): RedirectResponse
    {
        $this->personaService->crear($request->toDTO());

        return redirect()->route('personas.index')
            ->with('success', 'Persona registrada exitosamente.');
    }

    public function update(UpdatePersonaRequest $request, Personas $persona): RedirectResponse
    {
        $this->personaService->actualizar($persona, $request->toDTO());

        return redirect()->route('personas.index')
            ->with('success', 'Persona actualizada exitosamente.');
    }

    public function destroy(Personas $persona): RedirectResponse
    {
        $this->personaService->eliminar($persona);

        return redirect()->route('personas.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }

    public function convertirTrabajador(Personas $persona): RedirectResponse
    {
        $this->personaService->convertirEnTrabajador($persona);

        return redirect()->route('personas.index')
            ->with('success', 'Persona convertida en trabajador exitosamente.');
    }
}
