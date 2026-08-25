<?php

namespace App\Services\Horario;

use App\DTOs\Horario\CreateHorarioCursoDTO;
use App\DTOs\Horario\UpdateHorarioCursoDTO;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Models\Conasis\ConasisHorariosCursos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HorarioCursoService
{
    public function __construct(
        protected HorarioTrabajadorService $horarioTrabajadorService
    ) {}

    /**
     * Listar horarios de cursos por sección y año.
     *
     * @return Collection<int, ConasisHorariosCursos>
     */
    public function listarPorSeccion(int $seccionId, int $anio)
    {
        return ConasisHorariosCursos::query()
            ->with([
                'curso',
                'seccion.grado',
                'cargas.trabajador.persona',
                'cargas.trabajador.altas' => function ($q) {
                    $q->whereNull('fechaBaja')->with(['cargo', 'institucionEducativa']);
                },
                'detallesIni:id,horarioCursoIni_id,turno_id,nombreTurno',
            ])
            ->where('seccion_id', $seccionId)
            ->where('anio', $anio)
            ->get();
    }

    /**
     * Crear un nuevo horario de curso.
     */
    public function crear(CreateHorarioCursoDTO $dto): ConasisHorariosCursos
    {
        return ConasisHorariosCursos::create($dto->toArray());
    }

    /**
     * Actualizar un horario de curso existente y regenerar el horario del docente asignado.
     */
    public function actualizar(ConasisHorariosCursos $horarioCurso, UpdateHorarioCursoDTO $dto, ?int $turnoId = null): bool
    {
        return DB::transaction(function () use ($horarioCurso, $dto, $turnoId) {
            $updated = $horarioCurso->update($dto->toArray());

            if ($updated) {
                $cargas = ConasisCargaHoraria::where('horarioCurso_id', $horarioCurso->id)->get();
                $turnosPorDia = $turnoId ? [$horarioCurso->nroDia => $turnoId] : [];

                foreach ($cargas as $carga) {
                    $ieId = $horarioCurso->seccion->grado->institucionEduc_id;
                    $this->horarioTrabajadorService->regenerarDesdeCargas(
                        $carga->trabajador_id,
                        $horarioCurso->anio,
                        $ieId,
                        $turnosPorDia,
                    );
                }
            }

            return $updated;
        });
    }

    /**
     * Eliminar un horario de curso y regenerar el horario del docente asignado.
     */
    public function eliminar(ConasisHorariosCursos $horarioCurso): bool
    {
        return DB::transaction(function () use ($horarioCurso) {
            $cargas = ConasisCargaHoraria::where('horarioCurso_id', $horarioCurso->id)->get();
            $ieId = $horarioCurso->seccion->grado->institucionEduc_id;

            // Eliminar las cargas horarias asociadas
            ConasisCargaHoraria::where('horarioCurso_id', $horarioCurso->id)->delete();

            // Regenerar el horario para cada docente afectado ANTES de eliminar el horario del curso
            foreach ($cargas as $carga) {
                $this->horarioTrabajadorService->regenerarDesdeCargas(
                    $carga->trabajador_id,
                    $horarioCurso->anio,
                    $ieId
                );
            }

            // Eliminar el horario del curso
            $deleted = $horarioCurso->delete();

            return $deleted;
        });
    }
}
