<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreSubRecursoIERequest;
use App\Models\GradosIE;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\GradoIEService;
use Illuminate\Http\RedirectResponse;

class GradoIEController extends Controller
{
    public function __construct(
        private GradoIEService $gradoService,
    ) {}

    public function store(StoreSubRecursoIERequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->gradoService->crear($institucione, $request->validated());

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Grado registrado exitosamente.');
    }

    public function update(StoreSubRecursoIERequest $request, GradosIE $grado): RedirectResponse
    {
        $this->gradoService->actualizar($grado, $request->validated());

        return redirect()->route('instituciones.show', $grado->institucionEduc_id)
            ->with('success', 'Grado actualizado exitosamente.');
    }

    public function destroy(GradosIE $grado): RedirectResponse
    {
        $ieId = $grado->institucionEduc_id;
        $this->gradoService->eliminar($grado);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Grado desactivado exitosamente.');
    }
}
