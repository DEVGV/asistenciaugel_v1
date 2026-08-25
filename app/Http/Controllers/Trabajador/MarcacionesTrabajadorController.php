<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Models\Conasis\ConasisAsistencia;
use App\Models\Conasis\ConasisAsistenciaMesTrabajador;
use App\Models\Conasis\ConasisMarcaciones;
use App\Models\Trabajador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarcacionesTrabajadorController extends Controller
{
    /**
     * Lista las marcaciones de un trabajador, paginadas por mes/año.
     */
    public function porTrabajador(Request $request, Trabajador $trabajador): JsonResponse
    {
        $anio = $request->integer('anio') ?: (int) date('Y');
        $mes = $request->integer('mes') ?: (int) date('n');

        $marcaciones = ConasisMarcaciones::query()
            ->where('trabajador_id', $trabajador->id)
            ->whereYear('fechaMarcacion', $anio)
            ->whereMonth('fechaMarcacion', $mes)
            ->orderBy('fechaMarcacion')
            ->get([
                'id',
                'codigo',
                'fechaMarcacion',
                'tipo',
                'medioMarcacion',
                'procesado',
                'altaTrabajador_id',
            ]);

        return response()->json([
            'data' => $marcaciones,
        ]);
    }

    /**
     * Retorna el detalle consolidado mensual (t_asistenciaMesTrabajador) de un trabajador.
     * Si no hay consolidado procesado, retorna data null.
     */
    public function asistenciaConsolidada(Request $request, Trabajador $trabajador): JsonResponse
    {
        $anio = $request->integer('anio') ?: (int) date('Y');
        $mes  = $request->integer('mes')  ?: (int) date('n');

        $asistencia = ConasisAsistencia::query()
            ->where('trabajador_id', $trabajador->id)
            ->where('anio', $anio)
            ->where('mes', $mes)
            ->first();

        if (! $asistencia) {
            return response()->json(['data' => null]);
        }

        $turnos = ConasisAsistenciaMesTrabajador::query()
            ->with('turno:id,nombre')
            ->where('asistencia_id', $asistencia->id)
            ->orderBy('nroTurno')
            ->get();

        return response()->json([
            'data' => [
                'asistencia_id' => $asistencia->id,
                'turnos'        => $turnos,
            ],
        ]);
    }
}
