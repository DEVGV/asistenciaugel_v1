<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Http\Requests\Infraestructura\StoreLocalInstEducRequest;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\InstitucionesEduc;
use App\Services\Infraestructura\LocalInstEducService;
use Illuminate\Http\RedirectResponse;

class LocalInstEducController extends Controller
{
    public function __construct(
        private LocalInstEducService $localInstEducService,
    ) {}

    public function store(StoreLocalInstEducRequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->localInstEducService->crearYAsignar(
            $request->toLocalDTO(),
            $institucione,
            $request->validated('fechaInicio'),
            $request->validated('fechaFin'),
        );

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Local creado y asignado a la institución exitosamente.');
    }

    public function destroy(ConasisLocalesInstEduc $localesIe): RedirectResponse
    {
        $ieId = $localesIe->institucionEduc_id;
        $this->localInstEducService->desasignar($localesIe);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Local desasignado de la institución exitosamente.');
    }
}
