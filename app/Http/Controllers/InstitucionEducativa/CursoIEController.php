<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreSubRecursoIERequest;
use App\Models\CursosIE;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\CursoIEService;
use Illuminate\Http\RedirectResponse;

class CursoIEController extends Controller
{
    public function __construct(
        private CursoIEService $cursoService,
    ) {}

    public function store(StoreSubRecursoIERequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->cursoService->crear($institucione, $request->toDTO());

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Curso registrado exitosamente.');
    }

    public function update(StoreSubRecursoIERequest $request, CursosIE $curso): RedirectResponse
    {
        $this->cursoService->actualizar($curso, $request->toDTO());

        return redirect()->route('instituciones.show', $curso->institucionEduc_id)
            ->with('success', 'Curso actualizado exitosamente.');
    }

    public function destroy(CursosIE $curso): RedirectResponse
    {
        $ieId = $curso->institucionEduc_id;
        $this->cursoService->eliminar($curso);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Curso desactivado exitosamente.');
    }
}
