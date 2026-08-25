<?php

namespace App\Services\Horario;

use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Models\Conasis\ConasisDetalleHorarios;
use App\Models\Conasis\ConasisHorariosTrabajador;
use App\Models\InstitucionesEduc;
use App\Services\Auth\ContextoService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HorarioTrabajadorService
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    /**
     * Listar horarios del trabajador por año con todas las relaciones para la vista JSON.
     *
     * @return Collection<int, ConasisHorariosTrabajador>
     */
    public function listarPorTrabajador(int $trabajadorId, int $anio): Collection
    {
        $horarios = ConasisHorariosTrabajador::query()
            ->with([
                'detalles.horarioCursoIni.curso',
                'detalles.horarioCursoIni.seccion.grado',
                'institucionEduc',
                'altaTrabajador.cargo',
            ])
            ->where('trabajador_id', $trabajadorId)
            ->where('anio', $anio)
            ->where('activo', true)
            ->get();

        // Cargar las cargas horarias (cursos individuales) para cada horario
        foreach ($horarios as $horario) {
            $cargas = ConasisCargaHoraria::query()
                ->where('trabajador_id', $horario->trabajador_id)
                ->whereHas('horarioCurso', function ($query) use ($horario) {
                    $query->where('anio', $horario->anio)
                        ->whereHas('seccion.grado', function ($q) use ($horario) {
                            $q->where('institucionEduc_id', $horario->institucionEduc_id);
                        });
                })
                ->with([
                    'horarioCurso.curso',
                    'horarioCurso.seccion.grado',
                ])
                ->get();

            $horario->setAttribute('cargas_horarias', $cargas);
        }

        return $horarios;
    }

    /**
     * Obtener horario con todos sus detalles precargados.
     */
    public function obtenerConDetalles(ConasisHorariosTrabajador $horarioTrabajador): ConasisHorariosTrabajador
    {
        return $horarioTrabajador->load([
            'detalles.turno',
            'detalles.horarioCursoIni.curso',
            'detalles.horarioCursoIni.seccion.grado',
            'institucionEduc',
            'trabajador.persona',
            'altaTrabajador.cargo',
        ]);
    }

    /**
     * Lista las instituciones educativas activas para el selector del índice de horarios.
     *
     * @return Collection<int, InstitucionesEduc>
     */
    public function listarInstitucionesActivas(): Collection
    {
        return $this->contextoService->filtrarInstituciones(InstitucionesEduc::query())
            ->where(function ($q) {
                $q->whereNull('fechaFin')
                    ->orWhere('fechaFin', '>=', now()->toDateString());
            })
            ->orderBy('nombreLegal')
            ->get();
    }

    /**
     * Resuelve un HorarioTrabajador por su ID o, en su defecto, por trabajador_id + año.
     * Devuelve null si no se encuentra.
     */
    public function resolverHorario(int $id, int $anio): ?ConasisHorariosTrabajador
    {
        $horario = ConasisHorariosTrabajador::find($id);

        if (! $horario) {
            $horario = ConasisHorariosTrabajador::where('trabajador_id', $id)
                ->where('anio', $anio)
                ->first();
        }

        return $horario;
    }

    /**
     * Carga las cargas horarias del trabajador/año/IE para la vista Show.
     *
     * @return Collection<int, ConasisCargaHoraria>
     */
    public function listarCargasParaShow(ConasisHorariosTrabajador $horario): Collection
    {
        return ConasisCargaHoraria::query()
            ->where('trabajador_id', $horario->trabajador_id)
            ->whereHas('horarioCurso', function ($query) use ($horario) {
                $query->where('anio', $horario->anio)
                    ->whereHas('seccion.grado', function ($q) use ($horario) {
                        $q->where('institucionEduc_id', $horario->institucionEduc_id);
                    });
            })
            ->with([
                'horarioCurso.curso',
                'horarioCurso.seccion.grado',
            ])
            ->get();
    }

    /**
     * Crear o actualizar un horario manual (tipo A) para trabajadores
     * que no tienen cursos asignados (directores, auxiliares, administrativos).
     *
     * @param  array<int, array<string, mixed>>  $detalles  Detalles por día
     */
    public function guardarHorarioManual(array $data, array $detalles): ConasisHorariosTrabajador
    {
        return DB::transaction(function () use ($data, $detalles) {
            $trabajadorId = (int) $data['trabajador_id'];
            $anio = (int) $data['anio'];
            $ieId = (int) $data['institucionEduc_id'];

            // Buscar la alta activa del trabajador en la IE
            $alta = AltasTrabajadores::query()
                ->where('trabajador_id', $trabajadorId)
                ->where('institucionEducativa_id', $ieId)
                ->whereNull('fechaBaja')
                ->first();

            // Si ya existe un horario, preservar su tipoHorario; si es nuevo, usar 'A'
            $existente = ConasisHorariosTrabajador::query()
                ->where('anio', $anio)
                ->where('institucionEduc_id', $ieId)
                ->where('trabajador_id', $trabajadorId)
                ->first();

            $horario = ConasisHorariosTrabajador::updateOrCreate(
                [
                    'anio' => $anio,
                    'institucionEduc_id' => $ieId,
                    'trabajador_id' => $trabajadorId,
                ],
                [
                    'altaTrabajador_id' => $alta?->id ?? ($data['altaTrabajador_id'] ?? null),
                    'nombre' => $data['nombre'] ?? $existente?->nombre ?? 'Horario Trabajador '.$anio,
                    'tipoHorario' => $existente?->tipoHorario ?? 'A',
                    'fechaInicio' => $data['fechaInicio'] ?? $existente?->fechaInicio ?? $alta?->fechaInicio ?? Carbon::create($anio, 1, 1)->toDateString(),
                    'fechaFin' => $data['fechaFin'] ?? $existente?->fechaFin ?? $alta?->fechaFin,
                    'archivado' => false,
                    'activo' => true,
                    'created_by' => auth()->id() ?? 1,
                ]
            );

            // Sincronizar detalles: borrar los que no vienen y crear/actualizar
            $incomingIds = collect($detalles)->pluck('id')->filter()->toArray();
            ConasisDetalleHorarios::where('horarioTrabajador_id', $horario->id)
                ->whereNotIn('id', $incomingIds)
                ->delete();

            foreach ($detalles as $d) {
                $horaEntrada = $d['entHoraInicio'] ?? null;
                $horaSalida = $d['salHoraInicio'] ?? null;

                // Usar turno enviado o calcular por hora de entrada como fallback
                $turnoId = $d['turno_id'] ?? null;
                $nombreTurno = $d['nombreTurno'] ?? null;
                if (! $turnoId) {
                    if ($horaEntrada) {
                        $hora = (int) explode(':', $horaEntrada)[0];
                        if ($hora < 13) {
                            $turnoId = 1;
                            $nombreTurno = 'MAÑANA';
                        } elseif ($hora < 18) {
                            $turnoId = 2;
                            $nombreTurno = 'TARDE';
                        } else {
                            $turnoId = 3;
                            $nombreTurno = 'NOCHE';
                        }
                    } else {
                        $turnoId = 1;
                        $nombreTurno = 'MAÑANA';
                    }
                }

                // Calcular horas acumuladas
                $horasAcum = 0;
                if ($horaEntrada && $horaSalida) {
                    [$h1, $m1] = array_map('intval', explode(':', $horaEntrada));
                    [$h2, $m2] = array_map('intval', explode(':', $horaSalida));
                    $horasAcum = (($h2 * 60 + $m2) - ($h1 * 60 + $m1)) / 60.0;
                }

                // Calcular rangos de marcación con el margen configurado
                $rangos = ($horaEntrada && $horaSalida)
                    ? DetalleHorarioService::calcularRangos($horaEntrada, $horaSalida)
                    : ['entHoraInicio' => $horaEntrada, 'entHoraFin' => $horaEntrada, 'salHoraInicio' => $horaSalida, 'salHoraFin' => $horaSalida];

                ConasisDetalleHorarios::updateOrCreate(
                    [
                        'id' => $d['id'] ?? null,
                        'horarioTrabajador_id' => $horario->id,
                    ],
                    [
                        'turno_id' => $turnoId,
                        'nombreTurno' => $nombreTurno,
                        'nroTurno' => $turnoId,
                        'diaSemana' => 'S',
                        'nroDia' => $d['nroDia'],
                        'horarioCursoIni_id' => null,
                        'entDiaInicio' => 0,
                        'entDiaFin' => 0,
                        'entHoraInicio' => $rangos['entHoraInicio'],
                        'entHoraFin' => $rangos['entHoraFin'],
                        'entTolerancia' => 0,
                        'horarioCursoFin_id' => null,
                        'salDiaInicio' => 0,
                        'salDiaFin' => 0,
                        'salHoraInicio' => $rangos['salHoraInicio'],
                        'salHoraFin' => $rangos['salHoraFin'],
                        'salTolerancia' => 0,
                        'horaAcumula' => $d['horaAcumula'] ?? $horasAcum,
                        'aplicar' => $d['aplicar'] ?? true,
                        'created_by' => auth()->id() ?? 1,
                    ]
                );
            }

            return $horario->load(['detalles', 'institucionEduc', 'trabajador.persona', 'altaTrabajador.cargo']);
        });
    }

    /**
     * Regenerar el horario del docente y sus detalles basándose en su carga horaria.
     *
     * @param  array<int, int>  $turnosPorDia  nroDia → turno_id seleccionado por el usuario (opcional)
     */
    public function regenerarDesdeCargas(int $trabajadorId, int $anio, int $ieId, array $turnosPorDia = []): ?ConasisHorariosTrabajador
    {
        return DB::transaction(function () use ($trabajadorId, $anio, $ieId, $turnosPorDia) {
            // 1. Obtener todas las cargas horarias activas para el trabajador, año e IE
            $cargas = ConasisCargaHoraria::query()
                ->where('trabajador_id', $trabajadorId)
                ->whereHas('horarioCurso', function ($query) use ($anio, $ieId) {
                    $query->where('anio', $anio)
                        ->whereHas('seccion.grado', function ($q) use ($ieId) {
                            $q->where('institucionEduc_id', $ieId);
                        });
                })
                ->with(['horarioCurso'])
                ->get();

            // 2. Si no hay cargas horarias, desactivar el horario actual y salir
            if ($cargas->isEmpty()) {
                $horarioExistente = ConasisHorariosTrabajador::query()
                    ->where('trabajador_id', $trabajadorId)
                    ->where('anio', $anio)
                    ->where('institucionEduc_id', $ieId)
                    ->first();

                if ($horarioExistente) {
                    $horarioExistente->update(['activo' => false]);
                    ConasisDetalleHorarios::where('horarioTrabajador_id', $horarioExistente->id)->delete();
                }

                return null;
            }

            // Buscar la alta del trabajador activa en esta IE
            $alta = AltasTrabajadores::query()
                ->where('trabajador_id', $trabajadorId)
                ->where('institucionEducativa_id', $ieId)
                ->whereNull('fechaBaja')
                ->first();

            // 3. Crear o actualizar el HorarioTrabajador
            $horarioTrabajador = ConasisHorariosTrabajador::updateOrCreate(
                [
                    'anio' => $anio,
                    'institucionEduc_id' => $ieId,
                    'trabajador_id' => $trabajadorId,
                ],
                [
                    'altaTrabajador_id' => $alta?->id ?? $cargas->first()->altaTrabajador_id,
                    'nombre' => 'Horario del Docente '.$anio,
                    'tipoHorario' => '1', // Horario Regular
                    'fechaInicio' => $alta?->fechaInicio ?? Carbon::create($anio, 1, 1)->toDateString(),
                    'fechaFin' => $alta?->fechaFin,
                    'archivado' => false,
                    'activo' => true,
                    'created_by' => auth()->id() ?? 1,
                ]
            );

            // 4. Eliminar detalles anteriores para regenerar
            ConasisDetalleHorarios::where('horarioTrabajador_id', $horarioTrabajador->id)->delete();

            // 5. Agrupar las cargas por día (nroDia)
            $cargasPorDia = $cargas->groupBy(function ($carga) {
                return $carga->horarioCurso->nroDia;
            });

            // 6. Para cada día, generar un DetalleHorario consolidado
            foreach ($cargasPorDia as $nroDia => $cargasDia) {
                $cursosDia = $cargasDia->map(fn ($c) => $c->horarioCurso);

                // Encontrar el curso que inicia más temprano
                $cursoIni = $cursosDia->sortBy('horaInicio')->first();
                // Encontrar el curso que finaliza más tarde
                $cursoFin = $cursosDia->sortByDesc('horaFin')->first();

                $minHoraInicio = $cursoIni->horaInicio;
                $maxHoraFin = $cursoFin->horaFin;

                // Calcular horas acumuladas en minutos, luego en decimal de horas
                $totalMinutos = $cursosDia->sum('minAcum');
                $horasAcumuladas = $totalMinutos / 60.0;

                // Usar el turno seleccionado por el usuario si fue enviado.
                // Fallback: calcular por hora de inicio.
                $turnoSeleccionado = $turnosPorDia[$nroDia] ?? null;

                if ($turnoSeleccionado) {
                    $turnoId = $turnoSeleccionado;
                    $nombreTurno = match ($turnoId) {
                        1 => 'MAÑANA',
                        2 => 'TARDE',
                        3 => 'NOCHE',
                        default => 'MAÑANA',
                    };
                } else {
                    $inicioParse = Carbon::parse($minHoraInicio);
                    if ($inicioParse->hour < 13) {
                        $turnoId = 1;
                        $nombreTurno = 'MAÑANA';
                    } elseif ($inicioParse->hour < 18) {
                        $turnoId = 2;
                        $nombreTurno = 'TARDE';
                    } else {
                        $turnoId = 3;
                        $nombreTurno = 'NOCHE';
                    }
                }

                // Calcular rangos de marcación usando el margen configurado
                $rangos = DetalleHorarioService::calcularRangos($minHoraInicio, $maxHoraFin);

                ConasisDetalleHorarios::create([
                    'horarioTrabajador_id' => $horarioTrabajador->id,
                    'turno_id' => $turnoId,
                    'nombreTurno' => $nombreTurno,
                    'nroTurno' => $turnoId,
                    'diaSemana' => 'S', // S=semanal, para que la función use día-de-la-semana (DOW)
                    'nroDia' => $nroDia,
                    'horarioCursoIni_id' => $cursoIni->id,
                    'entDiaInicio' => 0,
                    'entDiaFin' => 0,
                    'entHoraInicio' => $rangos['entHoraInicio'],
                    'entHoraFin' => $rangos['entHoraFin'],
                    'entTolerancia' => 0,
                    'horarioCursoFin_id' => $cursoFin->id,
                    'salDiaInicio' => 0,
                    'salDiaFin' => 0,
                    'salHoraInicio' => $rangos['salHoraInicio'],
                    'salHoraFin' => $rangos['salHoraFin'],
                    'salTolerancia' => 0,
                    'horaAcumula' => $horasAcumuladas,
                    'aplicar' => true,
                    'created_by' => auth()->id() ?? 1,
                ]);
            }

            return $horarioTrabajador;
        });
    }
}
