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

    private function redirectToTrabajador(Personas $persona, string $message): RedirectResponse
    {
        $trabajador = $persona->trabajador;

        return $trabajador
            ? redirect()->route('trabajadores.show', $trabajador)->with('success', $message)
            : redirect()->route('trabajadores.index')->with('success', $message);
    }

    public function store(StoreDomicilioRequest $request, Personas $persona): RedirectResponse
    {
        $this->domicilioService->crear($persona, $request->toDTO());

        return $this->redirectToTrabajador($persona, 'Domicilio agregado exitosamente.');
    }

    public function update(StoreDomicilioRequest $request, Domicilios $domicilio): RedirectResponse
    {
        $this->domicilioService->actualizar($domicilio, $request->toDTO());

        return $this->redirectToTrabajador($domicilio->persona, 'Domicilio actualizado exitosamente.');
    }

    public function darDeBaja(Domicilios $domicilio): RedirectResponse
    {
        $this->domicilioService->darDeBaja($domicilio);

        return $this->redirectToTrabajador($domicilio->persona, 'Domicilio dado de baja exitosamente.');
    }

    public function destroy(Domicilios $domicilio): RedirectResponse
    {
        $persona = $domicilio->persona;
        $this->domicilioService->eliminar($domicilio);

        return $this->redirectToTrabajador($persona, 'Domicilio eliminado exitosamente.');
    }
}
