<?php

namespace App\Http\Controllers\Horario;

use App\Http\Controllers\Controller;
use App\Models\Trabajador;
use App\Services\Horario\HorarioTrabajadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HorarioTrabajadorController extends Controller
{
    public function __construct(
        private HorarioTrabajadorService $horarioTrabajadorService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('horario/Index', [
            'instituciones' => $this->horarioTrabajadorService->listarInstitucionesActivas(),
            'anioActual'    => (int) date('Y'),
        ]);
    }

    public function porTrabajador(Request $request, Trabajador $trabajador): JsonResponse
    {
        $anio = $request->integer('anio') ?: (int) date('Y');

        return response()->json([
            'data' => $this->horarioTrabajadorService->listarPorTrabajador($trabajador->id, $anio),
        ]);
    }

    public function show(Request $request, int $id): Response
    {
        $anio    = $request->integer('anio') ?: (int) date('Y');
        $horario = $this->horarioTrabajadorService->resolverHorario($id, $anio);

        abort_if(! $horario, 404, 'Horario de trabajador no encontrado.');

        return Inertia::render('horario/Show', [
            'horario' => $this->horarioTrabajadorService->obtenerConDetalles($horario),
            'cargas'  => $this->horarioTrabajadorService->listarCargasParaShow($horario),
        ]);
    }
}
