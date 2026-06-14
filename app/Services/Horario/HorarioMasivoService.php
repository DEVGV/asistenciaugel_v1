<?php

namespace App\Services\Horario;

use App\DTOs\Horario\CreateCargaHorariaDTO;
use App\DTOs\Horario\CreateHorarioCursoDTO;
use App\DTOs\Horario\UpdateHorarioCursoDTO;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Models\Conasis\ConasisHorariosCursos;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HorarioMasivoService
{
    public function __construct(
        private HorarioTrabajadorService $horarioTrabajadorService,
    ) {}

    /**
     * Crea o actualiza horarios de cursos de forma masiva, incluyendo la asignación
     * de docentes, y regenera el HorarioTrabajador al final para todos los afectados.
     *
     * Casuísticas:
     *  - id nulo   → crear; si ya existe duplicado (sección+curso+día+año) → actualizar
     *  - id número → actualizar el HorarioCurso existente
     *  - trabajador_id presente → crear o actualizar CargaHoraria para ese HorarioCurso
     *  - Si cambia el docente → se regenera también el horario del anterior
     *  - Regeneración agrupada al final: una sola llamada por trabajador+IE+año
     *
     * @param  array<int, array<string, mixed>>  $filas  Validadas por StoreHorarioMasivoRequest
     * @return array{
     *     creados: int,
     *     actualizados: int,
     *     docentes_asignados: int,
     *     docentes_actualizados: int,
     *     errores: array<int, string>
     * }
     */
    public function procesarMasivo(array $filas): array
    {
        $creados         = 0;
        $actualizados    = 0;
        $docentesAsig    = 0;
        $docentesActual  = 0;
        $errores         = [];

        // Pre-cargar IDs válidos de trabajadores para evitar N+1
        $trabIds = collect($filas)
            ->pluck('trabajador_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $validTrabajadores = Trabajador::whereIn('id', $trabIds)
            ->pluck('id')
            ->flip();

        // Acumulador para regeneración agrupada: trabId → anio → [ieId, ...]
        $afectados = [];

        DB::transaction(function () use (
            $filas,
            $validTrabajadores,
            &$creados,
            &$actualizados,
            &$docentesAsig,
            &$docentesActual,
            &$errores,
            &$afectados
        ) {
            foreach ($filas as $idx => $fila) {
                $rowNum = $idx + 1;

                try {
                    $horarioCurso = $this->resolverHorarioCurso($fila, $rowNum, $errores, $creados, $actualizados);
                } catch (\Throwable $e) {
                    $errores[$rowNum] = 'Error al guardar horario: ' . $e->getMessage();
                    continue;
                }

                if ($horarioCurso === null) {
                    continue;
                }

                if (empty($fila['trabajador_id'])) {
                    continue;
                }

                $trabajadorId = (int) $fila['trabajador_id'];

                if (! isset($validTrabajadores[$trabajadorId])) {
                    $errores[$rowNum] = ($errores[$rowNum] ?? '')
                        . " Trabajador id={$trabajadorId} no existe.";
                    continue;
                }

                try {
                    $this->resolverCargaHoraria(
                        $horarioCurso,
                        $fila,
                        $trabajadorId,
                        $afectados,
                        $docentesAsig,
                        $docentesActual,
                    );
                } catch (\Throwable $e) {
                    $errores[$rowNum] = ($errores[$rowNum] ?? '')
                        . ' Error al asignar docente: ' . $e->getMessage();
                }
            }
        });

        // Regenerar HorarioTrabajador fuera de la transacción principal,
        // una sola vez por trabajador+IE+año
        foreach ($afectados as $trabId => $anios) {
            foreach ($anios as $anio => $ieIds) {
                foreach (array_unique($ieIds) as $ieId) {
                    $this->horarioTrabajadorService->regenerarDesdeCargas(
                        (int) $trabId,
                        (int) $anio,
                        (int) $ieId,
                    );
                }
            }
        }

        return [
            'creados'              => $creados,
            'actualizados'         => $actualizados,
            'docentes_asignados'   => $docentesAsig,
            'docentes_actualizados' => $docentesActual,
            'errores'              => $errores,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers privados
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Crea o actualiza un HorarioCurso según la fila recibida.
     * Devuelve el modelo resultante, o null si hubo un error registrado en $errores.
     *
     * @param  array<string, mixed>  $fila
     * @param  array<int, string>    $errores  Pasado por referencia
     */
    private function resolverHorarioCurso(
        array $fila,
        int $rowNum,
        array &$errores,
        int &$creados,
        int &$actualizados
    ): ?ConasisHorariosCursos {
        $diaMap    = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
        $diaSemana = $fila['diaSemana'] ?? ($diaMap[$fila['nroDia']] ?? 'L');
        $minAcum   = $this->calcularMinAcum($fila['horaInicio'], $fila['horaFin']);

        $datos = array_merge($fila, [
            'diaSemana'  => $diaSemana,
            'minAcum'    => $minAcum,
            'created_by' => auth()->id() ?? 1,
        ]);

        if (! empty($fila['id'])) {
            $horarioCurso = ConasisHorariosCursos::find($fila['id']);

            if (! $horarioCurso) {
                $errores[$rowNum] = "HorarioCurso id={$fila['id']} no encontrado.";

                return null;
            }

            $horarioCurso->update(UpdateHorarioCursoDTO::from($datos)->toArray());
            $actualizados++;

            return $horarioCurso;
        }

        // Verificar duplicado sección+curso+día+año
        $existente = ConasisHorariosCursos::where([
            'seccion_id' => $fila['seccion_id'],
            'curso_id'   => $fila['curso_id'],
            'nroDia'     => $fila['nroDia'],
            'anio'       => $fila['anio'],
        ])->first();

        if ($existente) {
            $existente->update(UpdateHorarioCursoDTO::from($datos)->toArray());
            $actualizados++;

            return $existente;
        }

        $nuevo = ConasisHorariosCursos::create(
            CreateHorarioCursoDTO::from($datos)->toArray()
        );
        $creados++;

        return $nuevo;
    }

    /**
     * Crea o actualiza la CargaHoraria para un HorarioCurso dado.
     * Acumula los trabajadores afectados para regenerar su horario al final.
     *
     * @param  array<string, mixed>                       $fila
     * @param  array<int, array<int, array<int>>>         $afectados  Pasado por referencia
     */
    private function resolverCargaHoraria(
        ConasisHorariosCursos $horarioCurso,
        array $fila,
        int $trabajadorId,
        array &$afectados,
        int &$docentesAsig,
        int &$docentesActual
    ): void {
        $ieId = $horarioCurso->seccion->grado->institucionEduc_id ?? null;
        $anio = $fila['anio'];

        $dto = CreateCargaHorariaDTO::from([
            'horarioCurso_id'   => $horarioCurso->id,
            'trabajador_id'     => $trabajadorId,
            'altaTrabajador_id' => $fila['altaTrabajador_id'] ?? null,
            'titularSuplencia'  => $fila['titularSuplencia'] ?? 'T',
            'fechaInicio'       => $fila['fechaInicioDocente'] ?? null,
            'fechaFin'          => $fila['fechaFinDocente'] ?? null,
            'created_by'        => auth()->id() ?? 1,
        ]);

        $cargaExistente = ! empty($fila['carga_id'])
            ? ConasisCargaHoraria::find($fila['carga_id'])
            : ConasisCargaHoraria::where('horarioCurso_id', $horarioCurso->id)->first();

        if ($cargaExistente) {
            // Si cambió el docente, acumular el anterior para regenerar también
            if ($cargaExistente->trabajador_id !== $trabajadorId) {
                $this->acumular($afectados, $cargaExistente->trabajador_id, $anio, $ieId);
            }
            $cargaExistente->update($dto->toArray());
            $docentesActual++;
        } else {
            ConasisCargaHoraria::create($dto->toArray());
            $docentesAsig++;
        }

        $this->acumular($afectados, $trabajadorId, $anio, $ieId);
    }

    /** Calcula minutos acumulados entre dos horas string. Devuelve null si no es posible. */
    private function calcularMinAcum(string $inicio, string $fin): ?float
    {
        try {
            $ini = Carbon::parse($inicio);
            $end = Carbon::parse($fin);

            return $end->greaterThan($ini) ? (float) $ini->diffInMinutes($end) : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /** Agrega un trabajador al acumulador sin duplicar entradas. */
    private function acumular(array &$acum, int $trabId, int $anio, ?int $ieId): void
    {
        if (! $ieId) {
            return;
        }
        $acum[$trabId][$anio][] = $ieId;
    }
}
