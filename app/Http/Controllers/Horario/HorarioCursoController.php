<?php

namespace App\Http\Controllers\Horario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Horario\StoreHorarioCursoRequest;
use App\Http\Requests\Horario\UpdateHorarioCursoRequest;
use App\Models\Conasis\ConasisHorariosCursos;
use App\Services\Horario\HorarioCursoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HorarioCursoController extends Controller
{
    public function __construct(
        protected HorarioCursoService $horarioCursoService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $seccionId = $request->integer('seccion_id');
        $anio = $request->integer('anio') ?: (int) date('Y');

        $horarios = $this->horarioCursoService->listarPorSeccion($seccionId, $anio);

        return response()->json([
            'data' => $horarios,
        ]);
    }

    public function store(StoreHorarioCursoRequest $request): JsonResponse
    {
        $horarioCurso = $this->horarioCursoService->crear($request->toDTO());

        return response()->json([
            'message' => 'Horario de curso creado con éxito.',
            'data' => $horarioCurso,
        ], 201);
    }

    public function update(UpdateHorarioCursoRequest $request, ConasisHorariosCursos $horarioCurso): JsonResponse
    {
        $turnoId = $request->input('turno_id') ? (int) $request->input('turno_id') : null;
        $this->horarioCursoService->actualizar($horarioCurso, $request->toDTO(), $turnoId);

        return response()->json([
            'message' => 'Horario de curso actualizado con éxito.',
            'data' => $horarioCurso->fresh(),
        ]);
    }

    public function destroy(ConasisHorariosCursos $horarioCurso): JsonResponse
    {
        $this->horarioCursoService->eliminar($horarioCurso);

        return response()->json([
            'message' => 'Horario de curso eliminado con éxito.',
        ]);
    }
}
