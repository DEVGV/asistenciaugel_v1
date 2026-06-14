<?php

namespace App\Http\Controllers\Horario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Horario\StoreHorarioMasivoRequest;
use App\Services\Horario\HorarioMasivoService;
use Illuminate\Http\JsonResponse;

class HorarioMasivoController extends Controller
{
    public function __construct(
        private HorarioMasivoService $horarioMasivoService,
    ) {}

    public function store(StoreHorarioMasivoRequest $request): JsonResponse
    {
        $resultado = $this->horarioMasivoService->procesarMasivo($request->filas());

        $total = $resultado['creados'] + $resultado['actualizados'];

        if ($total === 0) {
            return response()->json([
                'message'              => 'No se procesó ningún horario.',
                'creados'              => 0,
                'actualizados'         => 0,
                'docentes_asignados'   => 0,
                'docentes_actualizados' => 0,
                'errores'              => $resultado['errores'],
            ], 422);
        }

        return response()->json([
            'message'              => "Procesados: {$resultado['creados']} creado(s), {$resultado['actualizados']} actualizado(s). Docentes: {$resultado['docentes_asignados']} asignado(s), {$resultado['docentes_actualizados']} actualizado(s).",
            'creados'              => $resultado['creados'],
            'actualizados'         => $resultado['actualizados'],
            'docentes_asignados'   => $resultado['docentes_asignados'],
            'docentes_actualizados' => $resultado['docentes_actualizados'],
            'errores'              => $resultado['errores'],
        ]);
    }
}
