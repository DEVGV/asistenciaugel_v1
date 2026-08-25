<?php

namespace App\Services\Horario;

use App\DTOs\Horario\CreateCargaHorariaDTO;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Models\Conasis\ConasisHorariosCursos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CargaHorariaService
{
    public function __construct(
        protected HorarioTrabajadorService $horarioTrabajadorService
    ) {}

    /**
     * Listar asignaciones de docentes para un horario de curso.
     *
     * @return Collection<int, ConasisCargaHoraria>
     */
    public function listarPorHorarioCurso(ConasisHorariosCursos $horarioCurso)
    {
        return ConasisCargaHoraria::query()
            ->with(['trabajador.persona', 'altaTrabajador'])
            ->where('horarioCurso_id', $horarioCurso->id)
            ->get();
    }

    /**
     * Asignar un docente a un horario de curso y regenerar su horario de entrada/salida.
     */
    public function asignar(CreateCargaHorariaDTO $dto, ?int $turnoId = null): ConasisCargaHoraria
    {
        return DB::transaction(function () use ($dto, $turnoId) {
            $carga = ConasisCargaHoraria::create($dto->toArray());

            $horarioCurso = $carga->horarioCurso;
            $ieId = $horarioCurso->seccion->grado->institucionEduc_id;
            $turnosPorDia = $turnoId ? [$horarioCurso->nroDia => $turnoId] : [];

            $this->horarioTrabajadorService->regenerarDesdeCargas(
                $carga->trabajador_id,
                $horarioCurso->anio,
                $ieId,
                $turnosPorDia,
            );

            return $carga;
        });
    }

    /**
     * Actualizar la asignación de un docente (cambiar trabajador, alta, fechas, condición).
     * Si cambia el trabajador, regenera el horario del anterior y del nuevo.
     */
    public function actualizar(ConasisCargaHoraria $cargaHoraria, CreateCargaHorariaDTO $dto, ?int $turnoId = null): ConasisCargaHoraria
    {
        return DB::transaction(function () use ($cargaHoraria, $dto, $turnoId) {
            $anteriorTrabajadorId = $cargaHoraria->trabajador_id;
            $horarioCurso = $cargaHoraria->horarioCurso;
            $ieId = $horarioCurso->seccion->grado->institucionEduc_id;
            $turnosPorDia = $turnoId ? [$horarioCurso->nroDia => $turnoId] : [];

            $cargaHoraria->update([
                'trabajador_id' => $dto->trabajador_id,
                'altaTrabajador_id' => $dto->altaTrabajador_id,
                'fechaInicio' => $dto->fechaInicio,
                'fechaFin' => $dto->fechaFin,
                'titularSuplencia' => $dto->titularSuplencia,
            ]);

            if ($anteriorTrabajadorId !== $dto->trabajador_id) {
                $this->horarioTrabajadorService->regenerarDesdeCargas(
                    $anteriorTrabajadorId,
                    $horarioCurso->anio,
                    $ieId,
                    $turnosPorDia,
                );
            }

            $this->horarioTrabajadorService->regenerarDesdeCargas(
                $dto->trabajador_id,
                $horarioCurso->anio,
                $ieId,
                $turnosPorDia,
            );

            return $cargaHoraria->fresh();
        });
    }

    /**
     * Eliminar la asignación de un docente a un curso y regenerar su horario.
     */
    public function desasignar(ConasisCargaHoraria $cargaHoraria): bool
    {
        return DB::transaction(function () use ($cargaHoraria) {
            $trabajadorId = $cargaHoraria->trabajador_id;
            $horarioCurso = $cargaHoraria->horarioCurso;
            $ieId = $horarioCurso->seccion->grado->institucionEduc_id;

            $deleted = $cargaHoraria->delete();

            // Regenerar el horario del docente desasignado
            $this->horarioTrabajadorService->regenerarDesdeCargas(
                $trabajadorId,
                $horarioCurso->anio,
                $ieId
            );

            return $deleted;
        });
    }
}
