<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Http\Requests\Persona\StoreTelefonoRequest;
use App\Models\Personas;
use App\Models\Telefonos;
use App\Services\Persona\TelefonoService;
use Illuminate\Http\RedirectResponse;

class TelefonoController extends Controller
{
    public function __construct(
        private TelefonoService $telefonoService,
    ) {}

    public function store(StoreTelefonoRequest $request, Personas $persona): RedirectResponse
    {
        $this->telefonoService->crear($persona, $request->validated());

        return redirect()->route('personas.show', $persona)
            ->with('success', 'Teléfono agregado exitosamente.');
    }

    public function update(StoreTelefonoRequest $request, Telefonos $telefono): RedirectResponse
    {
        $this->telefonoService->actualizar($telefono, $request->validated());

        return redirect()->route('personas.show', $telefono->persona_id)
            ->with('success', 'Teléfono actualizado exitosamente.');
    }

    public function destroy(Telefonos $telefono): RedirectResponse
    {
        $personaId = $telefono->persona_id;
        $this->telefonoService->eliminar($telefono);

        return redirect()->route('personas.show', $personaId)
            ->with('success', 'Teléfono eliminado exitosamente.');
    }
}
