<?php

namespace App\Http\Controllers\Horario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Horario\StoreCargaHorariaRequest;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Services\Horario\CargaHorariaService;
use Illuminate\Http\JsonResponse;

class CargaHorariaController extends Controller
{
    public function __construct(
        protected CargaHorariaService $cargaHorariaService
    ) {}

    public function store(StoreCargaHorariaRequest $request): JsonResponse
    {
        $turnoId = $request->input('turno_id') ? (int) $request->input('turno_id') : null;
        $carga = $this->cargaHorariaService->asignar($request->toDTO(), $turnoId);

        return response()->json([
            'message' => 'Docente asignado al curso con éxito.',
            'data' => $carga->load(['trabajador.persona']),
        ], 201);
    }

    public function update(StoreCargaHorariaRequest $request, ConasisCargaHoraria $cargaHoraria): JsonResponse
    {
        $turnoId = $request->input('turno_id') ? (int) $request->input('turno_id') : null;
        $carga = $this->cargaHorariaService->actualizar($cargaHoraria, $request->toDTO(), $turnoId);

        return response()->json([
            'message' => 'Asignación actualizada con éxito.',
            'data' => $carga->load(['trabajador.persona']),
        ]);
    }

    public function destroy(ConasisCargaHoraria $cargaHoraria): JsonResponse
    {
        $this->cargaHorariaService->desasignar($cargaHoraria);

        return response()->json([
            'message' => 'Docente desasignado del curso con éxito.',
        ]);
    }
}
