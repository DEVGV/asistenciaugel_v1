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

    private function redirectToTrabajador(Personas $persona, string $message): RedirectResponse
    {
        $trabajador = $persona->trabajador;

        return $trabajador
            ? redirect()->route('trabajadores.show', $trabajador)->with('success', $message)
            : redirect()->route('trabajadores.index')->with('success', $message);
    }

    public function store(StoreTelefonoRequest $request, Personas $persona): RedirectResponse
    {
        $this->telefonoService->crear($persona, $request->toDTO());

        return $this->redirectToTrabajador($persona, 'Teléfono agregado exitosamente.');
    }

    public function update(StoreTelefonoRequest $request, Telefonos $telefono): RedirectResponse
    {
        $this->telefonoService->actualizar($telefono, $request->toDTO());

        return $this->redirectToTrabajador($telefono->persona, 'Teléfono actualizado exitosamente.');
    }

    public function darDeBaja(Telefonos $telefono): RedirectResponse
    {
        $this->telefonoService->darDeBaja($telefono);

        return $this->redirectToTrabajador($telefono->persona, 'Teléfono dado de baja exitosamente.');
    }

    public function destroy(Telefonos $telefono): RedirectResponse
    {
        $persona = $telefono->persona;
        $this->telefonoService->eliminar($telefono);

        return $this->redirectToTrabajador($persona, 'Teléfono eliminado exitosamente.');
    }
}
