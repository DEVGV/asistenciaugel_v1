<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreDomicilioIERequest;
use App\Models\Domicilios;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\DomicilioIEService;
use Illuminate\Http\RedirectResponse;

class DomicilioIEController extends Controller
{
    public function __construct(
        private DomicilioIEService $domicilioService,
    ) {}

    public function store(StoreDomicilioIERequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->domicilioService->crear($institucione, $request->toDTO());

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Domicilio agregado exitosamente.');
    }

    public function update(StoreDomicilioIERequest $request, Domicilios $domicilio): RedirectResponse
    {
        $this->domicilioService->actualizar($domicilio, $request->toDTO());

        return redirect()->route('instituciones.show', $domicilio->institucionEduc_id)
            ->with('success', 'Domicilio actualizado exitosamente.');
    }

    public function darDeBaja(Domicilios $domicilio): RedirectResponse
    {
        $this->domicilioService->darDeBaja($domicilio);

        return redirect()->route('instituciones.show', $domicilio->institucionEduc_id)
            ->with('success', 'Domicilio dado de baja exitosamente.');
    }

    public function destroy(Domicilios $domicilio): RedirectResponse
    {
        $ieId = $domicilio->institucionEduc_id;
        $this->domicilioService->eliminar($domicilio);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Domicilio eliminado exitosamente.');
    }
}
