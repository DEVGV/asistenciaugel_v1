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
        $this->seccionService->crear($grado, $request->validated());

        return redirect()->route('instituciones.show', $grado->institucionEduc_id)
            ->with('success', 'Sección registrada exitosamente.');
    }

    public function update(StoreSubRecursoIERequest $request, SeccionesIE $seccione): RedirectResponse
    {
        $gradoId = $seccione->grado_id;
        $this->seccionService->actualizar($seccione, $request->validated());

        $grado = GradosIE::find($gradoId);

        return redirect()->route('instituciones.show', $grado->institucionEduc_id)
            ->with('success', 'Sección actualizada exitosamente.');
    }

    public function destroy(SeccionesIE $seccione): RedirectResponse
    {
        $grado = GradosIE::find($seccione->grado_id);
        $this->seccionService->eliminar($seccione);

        return redirect()->route('instituciones.show', $grado->institucionEduc_id)
            ->with('success', 'Sección desactivada exitosamente.');
    }
}
