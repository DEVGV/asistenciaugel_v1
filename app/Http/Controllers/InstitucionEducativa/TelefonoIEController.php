<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreTelefonoIERequest;
use App\Models\InstitucionesEduc;
use App\Models\Telefonos;
use App\Services\InstitucionEducativa\TelefonoIEService;
use Illuminate\Http\RedirectResponse;

class TelefonoIEController extends Controller
{
    public function __construct(
        private TelefonoIEService $telefonoService,
    ) {}

    public function store(StoreTelefonoIERequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->telefonoService->crear($institucione, $request->toDTO());

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Teléfono agregado exitosamente.');
    }

    public function update(StoreTelefonoIERequest $request, Telefonos $telefono): RedirectResponse
    {
        $this->telefonoService->actualizar($telefono, $request->toDTO());

        return redirect()->route('instituciones.show', $telefono->institucionEduc_id)
            ->with('success', 'Teléfono actualizado exitosamente.');
    }

    public function darDeBaja(Telefonos $telefono): RedirectResponse
    {
        $this->telefonoService->darDeBaja($telefono);

        return redirect()->route('instituciones.show', $telefono->institucionEduc_id)
            ->with('success', 'Teléfono dado de baja exitosamente.');
    }

    public function destroy(Telefonos $telefono): RedirectResponse
    {
        $ieId = $telefono->institucionEduc_id;
        $this->telefonoService->eliminar($telefono);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Teléfono eliminado exitosamente.');
    }
}
