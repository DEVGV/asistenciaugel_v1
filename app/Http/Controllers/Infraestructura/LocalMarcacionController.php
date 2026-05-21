<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Http\Requests\Infraestructura\StoreLocalMarcacionRequest;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Conasis\ConasisLocalesMarcacion;
use App\Services\Infraestructura\LocalMarcacionService;
use Illuminate\Http\RedirectResponse;

class LocalMarcacionController extends Controller
{
    public function __construct(
        private LocalMarcacionService $localMarcacionService,
    ) {}

    public function store(StoreLocalMarcacionRequest $request, ConasisLocalesInstEduc $localesIe): RedirectResponse
    {
        $this->localMarcacionService->asignar($request->toDTO());

        return redirect()->route('instituciones.show', $localesIe->institucionEduc_id)
            ->with('success', 'Trabajador asignado a marcación exitosamente.');
    }

    public function destroy(ConasisLocalesMarcacion $marcacionesLocal): RedirectResponse
    {
        $ieId = $marcacionesLocal->localInstEduc?->institucionEduc_id;
        $this->localMarcacionService->desasignar($marcacionesLocal);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Trabajador desasignado de marcación exitosamente.');
    }
}
