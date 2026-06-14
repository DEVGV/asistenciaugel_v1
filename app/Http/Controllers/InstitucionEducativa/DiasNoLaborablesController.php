<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiasNoLaborables\StoreDiasNoLaborablesRequest;
use App\Http\Requests\DiasNoLaborables\UpdateDiasNoLaborablesRequest;
use App\Models\Conasis\ConasisDiasNoLaborables;
use App\Models\InstitucionesEduc;
use App\Services\DiasNoLaborables\DiasNoLaborablesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DiasNoLaborablesController extends Controller
{
    public function __construct(
        private DiasNoLaborablesService $service,
    ) {}

    public function store(StoreDiasNoLaborablesRequest $request, InstitucionesEduc $institucione): RedirectResponse
    {
        $this->service->crear($request->toDTO());

        return redirect()->route('instituciones.show', $institucione)
            ->with('success', 'Día no laborable registrado.');
    }

    public function update(UpdateDiasNoLaborablesRequest $request, ConasisDiasNoLaborables $diasNoLaborable): RedirectResponse
    {
        $this->service->actualizar($diasNoLaborable, $request->toDTO());

        return redirect()->back()
            ->with('success', 'Día no laborable actualizado.');
    }

    public function destroy(ConasisDiasNoLaborables $diasNoLaborable): RedirectResponse
    {
        $this->service->eliminar($diasNoLaborable);

        return redirect()->back()
            ->with('success', 'Día no laborable eliminado.');
    }

    public function generarFeriados(Request $request, InstitucionesEduc $institucione): JsonResponse
    {
        $anio = (int) ($request->query('anio', now()->year));
        $generados = $this->service->generarFeriadosPorDefecto($institucione, $anio);

        return response()->json([
            'generados' => $generados,
            'message'   => $generados > 0
                ? "{$generados} feriado(s) generado(s) para {$anio}."
                : 'No se generaron nuevos feriados (ya existen o no hay feriados configurados).',
        ]);
    }
}
