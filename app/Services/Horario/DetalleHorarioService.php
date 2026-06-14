<?php

namespace App\Services\Horario;

use App\Models\Conasis\ConasisDetalleHorarios;
use App\Models\Conasis\ConasisHorariosTrabajador;
use Illuminate\Support\Facades\DB;

class DetalleHorarioService
{
    /**
     * Sincronizar detalles de horario para un horario de trabajador.
     *
     * @param  array<int, array<string, mixed>>  $detalles
     */
    public function sincronizar(ConasisHorariosTrabajador $horarioTrabajador, array $detalles): void
    {
        DB::transaction(function () use ($horarioTrabajador, $detalles) {
            // Obtener IDs de detalles que vienen en el request para borrar los omitidos
            $incomingIds = collect($detalles)->pluck('id')->filter()->toArray();

            // Eliminar detalles omitidos
            ConasisDetalleHorarios::where('horarioTrabajador_id', $horarioTrabajador->id)
                ->whereNotIn('id', $incomingIds)
                ->delete();

            // Crear o actualizar cada detalle
            foreach ($detalles as $detalleData) {
                ConasisDetalleHorarios::updateOrCreate(
                    [
                        'id' => $detalleData['id'] ?? null,
                        'horarioTrabajador_id' => $horarioTrabajador->id,
                    ],
                    [
                        'turno_id' => $detalleData['turno_id'] ?? null,
                        'nombreTurno' => $detalleData['nombreTurno'] ?? null,
                        'nroTurno' => $detalleData['nroTurno'] ?? null,
                        'diaSemana' => $detalleData['diaSemana'] ?? null,
                        'nroDia' => $detalleData['nroDia'] ?? null,
                        'horarioCursoIni_id' => $detalleData['horarioCursoIni_id'] ?? null,
                        'entDiaInicio' => $detalleData['entDiaInicio'] ?? 0,
                        'entDiaFin' => $detalleData['entDiaFin'] ?? 0,
                        'entHoraInicio' => $detalleData['entHoraInicio'] ?? null,
                        'entHoraFin' => $detalleData['entHoraFin'] ?? null,
                        'entTolerancia' => $detalleData['entTolerancia'] ?? 0,
                        'horarioCursoFin_id' => $detalleData['horarioCursoFin_id'] ?? null,
                        'salDiaInicio' => $detalleData['salDiaInicio'] ?? 0,
                        'salDiaFin' => $detalleData['salDiaFin'] ?? 0,
                        'salHoraInicio' => $detalleData['salHoraInicio'] ?? null,
                        'salHoraFin' => $detalleData['salHoraFin'] ?? null,
                        'salTolerancia' => $detalleData['salTolerancia'] ?? 0,
                        'horaAcumula' => $detalleData['horaAcumula'] ?? 0.0,
                        'aplicar' => isset($detalleData['aplicar']) ? filter_var($detalleData['aplicar'], FILTER_VALIDATE_BOOLEAN) : true,
                        'created_by' => auth()->id() ?? 1,
                    ]
                );
            }
        });
    }
}
