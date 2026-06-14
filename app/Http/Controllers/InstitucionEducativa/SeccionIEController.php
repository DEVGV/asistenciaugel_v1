<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreSubRecursoIERequest;
use App\Models\GradosIE;
use App\Models\SeccionesIE;
use App\Services\InstitucionEducativa\SeccionIEService;
use Illuminate\Http\RedirectResponse;

class SeccionIEController extends Controller
{
    public function __construct(
        private SeccionIEService $seccionService,
    ) {}

    public function store(StoreSubRecursoIERequest $request, GradosIE $grado): RedirectResponse
    {
        $this->seccionService->crear($grado, $request->toDTO());

        return redirect()->route('instituciones.show', $grado->institucionEduc_id)
            ->with('success', 'Sección registrada exitosamente.');
    }

    public function update(StoreSubRecursoIERequest $request, SeccionesIE $seccione): RedirectResponse
    {
        $ieId = $this->seccionService->obtenerIeId($seccione);
        $this->seccionService->actualizar($seccione, $request->toDTO());

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Sección actualizada exitosamente.');
    }

    public function destroy(SeccionesIE $seccione): RedirectResponse
    {
        $ieId = $this->seccionService->obtenerIeId($seccione);
        $this->seccionService->eliminar($seccione);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Sección desactivada exitosamente.');
    }
}
