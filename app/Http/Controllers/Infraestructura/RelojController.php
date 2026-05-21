<?php

namespace App\Http\Controllers\Infraestructura;

use App\DTOs\Infraestructura\CreateRelojDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Infraestructura\StoreRelojRequest;
use App\Http\Requests\Infraestructura\UpdateRelojRequest;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Conasis\ConasisRelojes;
use App\Services\Infraestructura\RelojService;
use Illuminate\Http\RedirectResponse;

class RelojController extends Controller
{
    public function __construct(
        private RelojService $relojService,
    ) {}

    public function store(StoreRelojRequest $request, ConasisLocalesInstEduc $localesIe): RedirectResponse
    {
        $data = array_merge($request->validated(), ['localInstEduc_id' => $localesIe->id]);
        $this->relojService->crear(CreateRelojDTO::from($data));

        return redirect()->route('instituciones.show', $localesIe->institucionEduc_id)
            ->with('success', 'Reloj registrado exitosamente.');
    }

    public function update(UpdateRelojRequest $request, ConasisRelojes $reloje): RedirectResponse
    {
        $this->relojService->actualizar($reloje, $request->toDTO());

        return redirect()->route('instituciones.show', $reloje->localInstEduc?->institucionEduc_id)
            ->with('success', 'Reloj actualizado exitosamente.');
    }

    public function destroy(ConasisRelojes $reloje): RedirectResponse
    {
        $ieId = $reloje->localInstEduc?->institucionEduc_id;
        $this->relojService->eliminar($reloje);

        return redirect()->route('instituciones.show', $ieId)
            ->with('success', 'Reloj desactivado exitosamente.');
    }
}
