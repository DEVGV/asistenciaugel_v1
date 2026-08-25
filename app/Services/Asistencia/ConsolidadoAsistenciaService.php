<?php

namespace App\Services\Asistencia;

use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisAsistencia;
use App\Models\Conasis\ConasisAsistenciaMesTrabajador;
use App\Models\Conasis\ConasisConsolAsistMesTrab;
use App\Models\InstitucionesEduc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConsolidadoAsistenciaService
{
    /**
     * Procesa la asistencia mensual de TODOS los trabajadores activos de una IE.
     * Llama a la función PostgreSQL f_procesa_asismes_v1 por cada trabajador.
     *
     * @return array{procesados: int, errores: array<int, array{trabajador_id: int, nombre: string, error: string}>, sin_horario: array<int, array{trabajador_id: int, nombre: string}>}
     */
    public function procesarMes(
        InstitucionesEduc $institucion,
        int $anio,
        int $mes,
        string $fechaDesde,
        string $fechaHasta,
        int $userId,
    ): array {
        // Obtener todos los trabajadores activos (sin baja) de la IE
        $altas = AltasTrabajadores::query()
            ->with('trabajador.persona')
            ->where('institucionEducativa_id', $institucion->id)
            ->whereNull('fechaBaja')
            ->where('fechaInicio', '<=', $fechaHasta)
            ->where(function ($q) use ($fechaDesde) {
                $q->whereNull('fechaFin')
                    ->orWhere('fechaFin', '>=', $fechaDesde);
            })
            ->get();

        $procesados = 0;
        $errores = [];
        $sinHorario = [];

        foreach ($altas as $alta) {
            $nombreCompleto = trim(
                ($alta->trabajador?->persona?->paterno ?? '') . ' ' .
                ($alta->trabajador?->persona?->materno ?? '') . ', ' .
                ($alta->trabajador?->persona?->nombre ?? '')
            );

            try {
                $resultado = DB::selectOne(
                    'SELECT conasis.f_procesa_asismes_v1(?::bigint, ?::bigint, ?::smallint, ?::smallint, ?::date, ?::date, ?::bigint) AS resultado',
                    [
                        $institucion->id,
                        $alta->trabajador_id,
                        $anio,
                        $mes,
                        $fechaDesde,
                        $fechaHasta,
                        $userId,
                    ]
                );

                if ($resultado->resultado === 'OK') {
                    $procesados++;
                } elseif ($resultado->resultado === 'NO_HOR') {
                    $sinHorario[] = [
                        'trabajador_id' => $alta->trabajador_id,
                        'nombre' => $nombreCompleto,
                    ];
                } else {
                    $errores[] = [
                        'trabajador_id' => $alta->trabajador_id,
                        'nombre' => $nombreCompleto,
                        'error' => $resultado->resultado ?? 'Resultado desconocido',
                    ];
                }
            } catch (\Throwable $e) {
                $errores[] = [
                    'trabajador_id' => $alta->trabajador_id,
                    'nombre' => $nombreCompleto,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'procesados' => $procesados,
            'errores' => $errores,
            'sin_horario' => $sinHorario,
            'total' => $altas->count(),
        ];
    }

    /**
     * Reprocesa la asistencia de UN solo trabajador.
     * Devuelve 'OK', 'NO_HOR' o un mensaje de error.
     */
    public function reprocesarTrabajador(
        InstitucionesEduc $institucion,
        int $trabajadorId,
        int $anio,
        int $mes,
        string $fechaDesde,
        string $fechaHasta,
        int $userId,
    ): array {
        try {
            $resultado = DB::selectOne(
                'SELECT conasis.f_procesa_asismes_v1(?::bigint, ?::bigint, ?::smallint, ?::smallint, ?::date, ?::date, ?::bigint) AS resultado',
                [$institucion->id, $trabajadorId, $anio, $mes, $fechaDesde, $fechaHasta, $userId]
            );

            $codigo = $resultado->resultado ?? 'ERROR';

            return [
                'ok'      => $codigo === 'OK',
                'codigo'  => $codigo,
                'mensaje' => match ($codigo) {
                    'OK'     => 'Asistencia reprocesada correctamente.',
                    'NO_HOR' => 'El trabajador no tiene horario asignado en el periodo.',
                    default  => "Resultado inesperado: {$codigo}",
                },
            ];
        } catch (\Throwable $e) {
            return [
                'ok'      => false,
                'codigo'  => 'ERROR',
                'mensaje' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtiene el consolidado de asistencia de una IE para un mes/año.
     *
     * @return \Illuminate\Support\Collection<int, ConasisAsistencia>
     */
    public function obtenerConsolidado(int $ieId, int $anio, int $mes)
    {
        return ConasisAsistencia::query()
            ->with([
                'trabajador.persona',
                'altaTrabajador.condicionLaboral',
                'altaTrabajador.rolTrabajador',
            ])
            ->where('institucionEduc_id', $ieId)
            ->where('anio', $anio)
            ->where('mes', $mes)
            ->orderBy('trabajador_id')
            ->get();
    }

    /**
     * Obtiene el detalle de asistencia diaria (la grilla mensual) por asistencia_id.
     *
     * @return \Illuminate\Support\Collection<int, ConasisAsistenciaMesTrabajador>
     */
    public function obtenerDetalleMensual(int $asistenciaId)
    {
        return ConasisAsistenciaMesTrabajador::query()
            ->with('turno')
            ->where('asistencia_id', $asistenciaId)
            ->orderBy('nroTurno')
            ->get();
    }

    /**
     * Obtiene los datos necesarios para el Reporte 1 (Anexo 03 - Asistencia Detallado).
     */
    public function obtenerDatosReporte1(InstitucionesEduc $ie, int $anio, int $mes): array
    {
        $ie->load(['entidadUgel', 'nivelCiclo']);

        $diasEnMes = Carbon::create($anio, $mes, 1)->daysInMonth;
        $letras = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        $diasSemana = [];
        for ($d = 1; $d <= $diasEnMes; $d++) {
            $diasSemana[$d] = $letras[Carbon::create($anio, $mes, $d)->dayOfWeek];
        }

        $asistencias = ConasisAsistencia::query()
            ->with([
                'trabajador.persona',
                'altaTrabajador.condicionLaboral',
                'altaTrabajador.cargo',
            ])
            ->where('institucionEduc_id', $ie->id)
            ->where('anio', $anio)
            ->where('mes', $mes)
            ->orderBy('trabajador_id')
            ->get();

        $asistenciaIds = $asistencias->pluck('id')->toArray();

        // Obtener todos los detalles mensuales de una vez
        $detalles = ConasisAsistenciaMesTrabajador::query()
            ->whereIn('asistencia_id', $asistenciaIds)
            ->orderBy('nroTurno')
            ->get()
            ->groupBy('asistencia_id');

        // Obtener turnos usados para el encabezado
        $turnosUsados = ConasisAsistenciaMesTrabajador::query()
            ->with('turno')
            ->whereIn('asistencia_id', $asistenciaIds)
            ->select('turno_id')
            ->distinct()
            ->get()
            ->pluck('turno.nombre')
            ->filter()
            ->implode(', ');

        // Cargar horarios para Jor. Lab.
        $fechaDesde = Carbon::create($anio, $mes, 1)->toDateString();
        $fechaHasta = Carbon::create($anio, $mes, $diasEnMes)->toDateString();
        $trabajadorIds = $asistencias->pluck('trabajador_id')->unique()->toArray();

        $horarios = DB::table('conasis.t_horariosTrabajador as ht')
            ->join('conasis.t_detalleHorarios as dh', 'dh.horarioTrabajador_id', '=', 'ht.id')
            ->where('ht.institucionEduc_id', $ie->id)
            ->whereIn('ht.trabajador_id', $trabajadorIds)
            ->where('ht.fechaInicio', '<=', $fechaHasta)
            ->where(function ($q) use ($fechaDesde) {
                $q->whereNull('ht.fechaFin')
                    ->orWhere('ht.fechaFin', '>=', $fechaDesde);
            })
            ->where('dh.aplicar', true)
            ->select('ht.trabajador_id', 'dh.horaAcumula', 'dh.nroDia')
            ->get()
            ->groupBy('trabajador_id');

        $trabajadores = [];
        foreach ($asistencias as $asistencia) {
            $turnoRows = $detalles->get($asistencia->id, collect());
            $primerTurno = $turnoRows->first();

            $dias = [];
            for ($d = 1; $d <= 31; $d++) {
                if ($primerTurno) {
                    $dias[$d] = $primerTurno->{'c' . $d} ?? '';
                } else {
                    $dias[$d] = '';
                }
            }

            // Si hay múltiples turnos, combinar
            if ($turnoRows->count() > 1) {
                foreach ($turnoRows->slice(1) as $otroTurno) {
                    for ($d = 1; $d <= 31; $d++) {
                        if (empty($dias[$d]) && ! empty($otroTurno->{'c' . $d})) {
                            $dias[$d] = $otroTurno->{'c' . $d};
                        }
                    }
                }
            }

            // Jornada laboral desde horario
            $jorLab = '';
            $sched = $horarios->get($asistencia->trabajador_id, collect());
            if ($sched->isNotEmpty()) {
                $totalHoras = (float) $sched->sum('horaAcumula');
                $diasConHorario = $sched->pluck('nroDia')->unique()->count();
                if ($diasConHorario > 0 && $totalHoras > 0) {
                    $jorLab = number_format($totalHoras / $diasConHorario, 1);
                }
            }

            $trabajadores[] = [
                'dni' => $asistencia->trabajador?->persona?->docIdentidad ?? '',
                'nombre' => trim(
                    ($asistencia->trabajador?->persona?->paterno ?? '') . ' ' .
                    ($asistencia->trabajador?->persona?->materno ?? '') . ', ' .
                    ($asistencia->trabajador?->persona?->nombre ?? '')
                ),
                'cargo' => $asistencia->altaTrabajador?->cargo?->nombre ?? '-',
                'condicion' => $asistencia->altaTrabajador?->condicionLaboral?->abreviatura ?? '-',
                'jorLab' => $jorLab,
                'dias' => $dias,
            ];
        }

        $mesesNombres = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return [
            'dreUgel' => $ie->entidadUgel?->razonSocial ?? '',
            'ieNombre' => $ie->nombreLegal ?? '',
            'nivelModalidad' => $ie->nivelCiclo?->nombre ?? '',
            'turno' => $turnosUsados ?: '',
            'anio' => $anio,
            'mes' => $mes,
            'mesNombre' => $mesesNombres[$mes] ?? '',
            'diasEnMes' => $diasEnMes,
            'diasSemana' => $diasSemana,
            'trabajadores' => $trabajadores,
        ];
    }

    /**
     * Obtiene los datos necesarios para el Reporte 2 (Anexo 04 - Consolidado Inasistencias).
     *
     * Nota: los campos resumen de t_asistencia (ndias_falt, nhoras_tarde, etc.)
     * NO son poblados por f_procesa_asismes_v1. Todos los datos se obtienen desde
     * t_consolAsistMesTrab (conteo de días por sigla) y t_asistenciaMesTrabajador
     * (horas de entrada para cálculo de minutos de tardanza).
     */
    public function obtenerDatosReporte2(InstitucionesEduc $ie, int $anio, int $mes): array
    {
        $ie->load(['entidadUgel', 'nivelCiclo']);
        $diasEnMes = Carbon::create($anio, $mes, 1)->daysInMonth;

        $asistencias = ConasisAsistencia::query()
            ->with([
                'trabajador.persona',
                'altaTrabajador.condicionLaboral',
                'altaTrabajador.cargo',
            ])
            ->where('institucionEduc_id', $ie->id)
            ->where('anio', $anio)
            ->where('mes', $mes)
            ->orderBy('trabajador_id')
            ->get();

        $asistenciaIds = $asistencias->pluck('id')->toArray();

        // Turnos para el encabezado
        $turnosUsados = ConasisAsistenciaMesTrabajador::query()
            ->with('turno')
            ->whereIn('asistencia_id', $asistenciaIds)
            ->select('turno_id')
            ->distinct()
            ->get()
            ->pluck('turno.nombre')
            ->filter()
            ->implode(', ');

        // ── Cargar consolAsistMesTrab (fuente principal de conteos) ──
        $consolData = DB::table('conasis.t_consolAsistMesTrab')
            ->whereIn('asistencia_id', $asistenciaIds)
            ->get()
            ->groupBy('asistencia_id');

        // ── Cargar detalle diario para cálculo de tardanza ──
        $detalles = ConasisAsistenciaMesTrabajador::query()
            ->whereIn('asistencia_id', $asistenciaIds)
            ->orderBy('nroTurno')
            ->get()
            ->groupBy('asistencia_id');

        // ── Cargar horarios (entHoraFin) para calcular minutos de tardanza ──
        $fechaDesde = Carbon::create($anio, $mes, 1)->toDateString();
        $fechaHasta = Carbon::create($anio, $mes, $diasEnMes)->toDateString();
        $trabajadorIds = $asistencias->pluck('trabajador_id')->unique()->toArray();

        $horarios = DB::table('conasis.t_horariosTrabajador as ht')
            ->join('conasis.t_detalleHorarios as dh', 'dh.horarioTrabajador_id', '=', 'ht.id')
            ->where('ht.institucionEduc_id', $ie->id)
            ->whereIn('ht.trabajador_id', $trabajadorIds)
            ->where('ht.fechaInicio', '<=', $fechaHasta)
            ->where(function ($q) use ($fechaDesde) {
                $q->whereNull('ht.fechaFin')
                    ->orWhere('ht.fechaFin', '>=', $fechaDesde);
            })
            ->where('dh.aplicar', true)
            ->select(
                'ht.trabajador_id',
                'dh.diaSemana',
                'dh.nroDia',
                'dh.entHoraFin',
                'dh.turno_id',
                'dh.nroTurno',
                'dh.horaAcumula',
            )
            ->get()
            ->groupBy('trabajador_id');

        $trabajadores = [];

        foreach ($asistencias as $asistencia) {
            $records = $consolData->get($asistencia->id, collect());

            // ── Inasistencias: F (falta injust.), 3T, 3E ──
            $inasistenciasDias = (float) $records->filter(
                fn ($r) => in_array($r->sigla, ['F', 'I', '3T', '3E'])
            )->sum('ndias');

            // ── Huelga / Paro ──
            $huelgaDias = (float) $records->where('sigla', 'H')->sum('ndias');

            // ── Permisos sin goce (L, P no remunerado) ──
            // remunerado puede ser boolean o string 't'/'f' según el driver
            $permisosSgDias = (float) $records->filter(
                fn ($r) => in_array($r->sigla, ['L', 'P'])
                    && $r->remunerado !== true && $r->remunerado !== 't'
            )->sum('ndias');

            // ── Calcular tardanza en minutos cronológicos ──
            $totalTardanzaMin = $this->calcularMinutosTardanza(
                $detalles->get($asistencia->id, collect()),
                $horarios->get($asistencia->trabajador_id, collect()),
                $anio,
                $mes,
                $diasEnMes,
            );

            $tardanzaHoras = intdiv($totalTardanzaMin, 60);
            $tardanzaMinutos = $totalTardanzaMin % 60;

            // ── Jornada laboral (horas diarias promedio del horario) ──
            $jorLab = '';
            $sched = $horarios->get($asistencia->trabajador_id, collect());
            if ($sched->isNotEmpty()) {
                $totalHorasHorario = (float) $sched->sum('horaAcumula');
                $diasDistintos = $sched->pluck('nroDia')->unique()->count();
                if ($diasDistintos > 0 && $totalHorasHorario > 0) {
                    $jorLab = number_format($totalHorasHorario / $diasDistintos, 1);
                }
            }

            // ── Permisos SG: convertir días a horas usando jornada ──
            $permisosSgHoras = 0;
            $permisosSgMinutos = 0;
            if ($permisosSgDias > 0 && $jorLab !== '') {
                $horasTotales = $permisosSgDias * (float) $jorLab;
                $permisosSgHoras = (int) floor($horasTotales);
                $permisosSgMinutos = (int) round(($horasTotales - $permisosSgHoras) * 60);
            }

            $trabajadores[] = [
                'dni' => $asistencia->trabajador?->persona?->docIdentidad ?? '',
                'nombre' => trim(
                    ($asistencia->trabajador?->persona?->paterno ?? '') . ' ' .
                    ($asistencia->trabajador?->persona?->materno ?? '') . ', ' .
                    ($asistencia->trabajador?->persona?->nombre ?? '')
                ),
                'cargo' => $asistencia->altaTrabajador?->cargo?->nombre ?? '-',
                'condicion' => $asistencia->altaTrabajador?->condicionLaboral?->abreviatura ?? '-',
                'jorLab' => $jorLab,
                'inasistencias_dias' => $inasistenciasDias,
                'tardanzas_horas' => $tardanzaHoras,
                'tardanzas_minutos' => $tardanzaMinutos,
                'permisos_sg_horas' => $permisosSgHoras,
                'permisos_sg_minutos' => $permisosSgMinutos,
                'huelga_dias' => $huelgaDias,
                'observaciones' => '',
            ];
        }

        $mesesNombres = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return [
            'dreUgel' => $ie->entidadUgel?->razonSocial ?? '',
            'ieNombre' => $ie->nombreLegal ?? '',
            'nivelModalidad' => $ie->nivelCiclo?->nombre ?? '',
            'turno' => $turnosUsados ?: '',
            'anio' => $anio,
            'mes' => $mes,
            'mesNombre' => $mesesNombres[$mes] ?? '',
            'trabajadores' => $trabajadores,
        ];
    }

    /**
     * Calcula los minutos totales de tardanza comparando la hora de entrada
     * real (e{d} en t_asistenciaMesTrabajador) contra la hora límite programada
     * (entHoraFin en t_detalleHorarios) para los días marcados como 'T'.
     */
    private function calcularMinutosTardanza(
        \Illuminate\Support\Collection $turnoRows,
        \Illuminate\Support\Collection $schedule,
        int $anio,
        int $mes,
        int $diasEnMes,
    ): int {
        $total = 0;

        foreach ($turnoRows as $turnoRow) {
            for ($d = 1; $d <= $diasEnMes; $d++) {
                $cond = $turnoRow->{'c' . $d} ?? '';
                // Solo contar tardanzas (incluye '3T' que son 3eras tardanzas)
                if ($cond !== 'T' && $cond !== '3T') {
                    continue;
                }

                $entradaStr = $turnoRow->{'e' . $d} ?? '';
                if (empty($entradaStr)) {
                    continue;
                }

                // El SP almacena e{d} como "08:15:00 (T) I1" — extraer solo la hora
                if (! preg_match('/^(\d{1,2}:\d{2}(:\d{2})?)/', $entradaStr, $matches)) {
                    continue;
                }
                $timeStr = $matches[1];

                // Buscar el horario programado para este día
                $fecha = Carbon::create($anio, $mes, $d);
                $dow = $fecha->dayOfWeek; // 0 = Domingo

                $schedEntry = $schedule->first(function ($s) use ($dow, $d, $turnoRow) {
                    if ($s->turno_id != $turnoRow->turno_id || $s->nroTurno != $turnoRow->nroTurno) {
                        return false;
                    }
                    // diaSemana='S' → nroDia = day of week (PostgreSQL dow: 0=Sun)
                    // diaSemana='D' → nroDia = day of month
                    return ($s->diaSemana === 'S' && (int) $s->nroDia === $dow)
                        || ($s->diaSemana === 'D' && (int) $s->nroDia === $d);
                });

                if (! $schedEntry || ! $schedEntry->entHoraFin) {
                    continue;
                }

                try {
                    $entrada = Carbon::parse($timeStr);
                    $limite = Carbon::parse($schedEntry->entHoraFin);

                    if ($entrada->gt($limite)) {
                        $total += (int) $limite->diffInMinutes($entrada);
                    }
                } catch (\Throwable) {
                    // Formato de hora inválido, ignorar
                }
            }
        }

        return $total;
    }

    /**
     * Obtiene el resumen consolidado (siglas y días) por asistencia_id.
     *
     * @return \Illuminate\Support\Collection<int, ConasisConsolAsistMesTrab>
     */
    public function obtenerResumenConsolidado(int $asistenciaId)
    {
        return DB::table('conasis.t_consolAsistMesTrab as c')
            ->leftJoin('param.t00_motivosSuspLab as m', 'c.motivoSuspLab_id', '=', 'm.id')
            ->where('c.asistencia_id', $asistenciaId)
            ->groupBy('c.sigla', 'c.siglaPers', 'c.motivoSuspLab_id', 'c.remunerado', 'm.descripcion', 'm.abreviaturaPers')
            ->havingRaw('SUM(c.ndias) > 0')
            ->orderBy('c.sigla')
            ->select([
                'c.sigla',
                'c.siglaPers',
                'c.motivoSuspLab_id',
                'c.remunerado',
                DB::raw('SUM(c.ndias) as ndias'),
                'm.descripcion as motivo_descripcion',
                'm.abreviaturaPers as motivo_abreviatura_pers',
            ])
            ->get();
    }
}
