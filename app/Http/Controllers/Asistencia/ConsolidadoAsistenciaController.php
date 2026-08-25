<?php

namespace App\Http\Controllers\Asistencia;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asistencia\ProcesarConsolidadoRequest;
use App\Http\Requests\Asistencia\ReprocesarTrabajadorRequest;
use App\Models\InstitucionesEduc;
use App\Services\Asistencia\ConsolidadoAsistenciaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsolidadoAsistenciaController extends Controller
{
    public function __construct(
        private ConsolidadoAsistenciaService $consolidadoService,
    ) {}

    /**
     * Procesa la asistencia mensual de todos los trabajadores de la IE.
     */
    public function procesar(ProcesarConsolidadoRequest $request, InstitucionesEduc $institucione): JsonResponse
    {
        $params = $request->toProcesarParams();

        $resultado = $this->consolidadoService->procesarMes(
            institucion: $institucione,
            anio: $params['anio'],
            mes: $params['mes'],
            fechaDesde: $params['fechaDesde'],
            fechaHasta: $params['fechaHasta'],
            userId: auth()->id(),
        );

        return response()->json([
            'message'     => "Procesamiento completado: {$resultado['procesados']} de {$resultado['total']} trabajadores procesados.",
            'procesados'  => $resultado['procesados'],
            'total'       => $resultado['total'],
            'sin_horario' => $resultado['sin_horario'],
            'errores'     => $resultado['errores'],
        ]);
    }

    /**
     * Reprocesa la asistencia de UN solo trabajador.
     * POST /instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador
     */
    public function reprocesarTrabajador(
        ReprocesarTrabajadorRequest $request,
        InstitucionesEduc $institucione,
    ): JsonResponse {
        $params = $request->toProcesarParams();

        $resultado = $this->consolidadoService->reprocesarTrabajador(
            institucion: $institucione,
            trabajadorId: $params['trabajador_id'],
            anio: $params['anio'],
            mes: $params['mes'],
            fechaDesde: $params['fechaDesde'],
            fechaHasta: $params['fechaHasta'],
            userId: auth()->id() ?? 1,
        );

        return response()->json($resultado, $resultado['ok'] ? 200 : 422);
    }

    /**
     * Obtiene el consolidado ya procesado para mostrar en la vista.
     */
    public function consultar(Request $request, InstitucionesEduc $institucione): JsonResponse
    {
        $anio = $request->integer('anio', (int) date('Y'));
        $mes  = $request->integer('mes', (int) date('m'));

        $consolidado = $this->consolidadoService->obtenerConsolidado(
            $institucione->id,
            $anio,
            $mes,
        );

        // Para cada registro de asistencia, obtener su resumen consolidado
        $resultados = $consolidado->map(function ($asistencia) {
            $resumen = $this->consolidadoService->obtenerResumenConsolidado($asistencia->id);

            return [
                'id'          => $asistencia->id,
                'trabajador'  => [
                    'id'     => $asistencia->trabajador_id,
                    'nombre' => trim(
                        ($asistencia->trabajador?->persona?->paterno ?? '') . ' ' .
                        ($asistencia->trabajador?->persona?->materno ?? '') . ', ' .
                        ($asistencia->trabajador?->persona?->nombre ?? '')
                    ),
                    'dni'    => $asistencia->trabajador?->persona?->docIdentidad ?? '',
                ],
                'condicion'   => $asistencia->altaTrabajador?->condicionLaboral?->abreviatura ?? '-',
                'rol'         => $asistencia->altaTrabajador?->rolTrabajador?->nombre ?? '-',
                'fechaDesde'  => $asistencia->fechaDesde,
                'fechaHasta'  => $asistencia->fechaHasta,
                'resumen'     => $resumen->map(fn ($r) => [
                    'sigla' => $r->sigla,
                    'sigla_pers' => $r->siglaPers ?? $r->motivo_abreviatura_pers ?? $r->sigla,
                    'ndias' => (float) $r->ndias,
                    'remunerado' => $r->remunerado,
                    'motivo' => $r->motivo_descripcion ?? $r->sigla,
                ]),
            ];
        });

        return response()->json(['data' => $resultados]);
    }

    /**
     * Obtiene el detalle diario de un registro de asistencia específico.
     */
    public function detalle(int $asistenciaId): JsonResponse
    {
        $detalle = $this->consolidadoService->obtenerDetalleMensual($asistenciaId);

        return response()->json(['data' => $detalle]);
    }

    /**
     * Genera el Reporte 1 (Anexo 03) - Reporte de Asistencia Detallado en PDF.
     */
    public function reporte1(Request $request, InstitucionesEduc $institucione): Response
    {
        $anio = $request->integer('anio', (int) date('Y'));
        $mes  = $request->integer('mes', (int) date('m'));

        $datos = $this->consolidadoService->obtenerDatosReporte1($institucione, $anio, $mes);

        $pdf = Pdf::loadView('reportes.reporte1-asistencia-detallado', $datos);
        $pdf->setPaper('A4', 'landscape');

        $filename = "Reporte_Asistencia_Detallado_{$datos['mesNombre']}_{$anio}.pdf";

        return $pdf->stream($filename);
    }

    /**
     * Genera el Reporte 2 (Anexo 04) - Consolidado de Inasistencias, Tardanzas y Permisos SG en PDF.
     */
    public function reporte2(Request $request, InstitucionesEduc $institucione): Response
    {
        $anio = $request->integer('anio', (int) date('Y'));
        $mes  = $request->integer('mes', (int) date('m'));

        $datos = $this->consolidadoService->obtenerDatosReporte2($institucione, $anio, $mes);

        $pdf = Pdf::loadView('reportes.reporte2-consolidado-inasistencias', $datos);
        $pdf->setPaper('A4', 'landscape');

        $filename = "Reporte_Consolidado_Inasistencias_{$datos['mesNombre']}_{$anio}.pdf";

        return $pdf->stream($filename);
    }
}
