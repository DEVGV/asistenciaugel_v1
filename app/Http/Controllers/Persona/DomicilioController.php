<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Http\Requests\Persona\StoreDomicilioRequest;
use App\Models\Domicilios;
use App\Models\Personas;
use App\Services\Persona\DomicilioService;
use Illuminate\Http\RedirectResponse;

class DomicilioController extends Controller
{
    public function __construct(
        private DomicilioService $domicilioService,
    ) {}

    public function store(StoreDomicilioRequest $request, Personas $persona): RedirectResponse
    {
        $this->domicilioService->crear($persona, $request->validated());

        return redirect()->route('personas.show', $persona)
            ->with('success', 'Domicilio agregado exitosamente.');
    }

    public function update(StoreDomicilioRequest $request, Domicilios $domicilio): RedirectResponse
    {
        $this->domicilioService->actualizar($domicilio, $request->validated());

        return redirect()->route('personas.show', $domicilio->persona_id)
            ->with('success', 'Domicilio actualizado exitosamente.');
    }

    public function destroy(Domicilios $domicilio): RedirectResponse
    {
        $personaId = $domicilio->persona_id;
        $this->domicilioService->eliminar($domicilio);

        return redirect()->route('personas.show', $personaId)
            ->with('success', 'Domicilio eliminado exitosamente.');
    }
}
