<?php

namespace App\Services\Horario;

use App\Models\Conasis\ConasisDetalleHorarios;
use App\Models\Conasis\ConasisHorariosTrabajador;
use Illuminate\Support\Facades\DB;

class DetalleHorarioService
{
    /**
     * Calcula entHoraInicio y salHoraFin aplicando el margen de marcación
     * definido en config('asistencia.margen_marcacion').
     *
     * Lógica:
     *   - entHoraInicio = horaEntrada - margen  (puede marcar desde aquí)
     *   - entHoraFin    = horaEntrada           (hora real de entrada)
     *   - salHoraInicio = horaSalida            (hora real de salida)
     *   - salHoraFin    = horaSalida + margen   (puede marcar hasta aquí)
     *
     * @return array{entHoraInicio: string, entHoraFin: string, salHoraInicio: string, salHoraFin: string}
     */
    public static function calcularRangos(string $horaEntrada, string $horaSalida): array
    {
        $margen = (int) config('asistencia.margen_marcacion', 30);

        $entHoraFin = self::normalizarHora($horaEntrada);
        $entHoraInicio = self::restarMinutos($entHoraFin, $margen);

        $salHoraInicio = self::normalizarHora($horaSalida);
        $salHoraFin = self::sumarMinutos($salHoraInicio, $margen);

        // Cubrir los 60 segundos del minuto borde en salHoraFin
        $salHoraFin = self::ajustarSegundos($salHoraFin, 59);

        return [
            'entHoraInicio' => $entHoraInicio,
            'entHoraFin' => $entHoraFin,
            'salHoraInicio' => $salHoraInicio,
            'salHoraFin' => $salHoraFin,
        ];
    }

    /**
     * Sincronizar detalles de horario para un horario de trabajador.
     *
     * @param  array<int, array<string, mixed>>  $detalles
     */
    public function sincronizar(ConasisHorariosTrabajador $horarioTrabajador, array $detalles): void
    {
        DB::connection('pgsql')->transaction(function () use ($horarioTrabajador, $detalles) {
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
                        'entTolerancia' => 0,
                        'horarioCursoFin_id' => $detalleData['horarioCursoFin_id'] ?? null,
                        'salDiaInicio' => $detalleData['salDiaInicio'] ?? 0,
                        'salDiaFin' => $detalleData['salDiaFin'] ?? 0,
                        'salHoraInicio' => $detalleData['salHoraInicio'] ?? null,
                        'salHoraFin' => $detalleData['salHoraFin'] ?? null,
                        'salTolerancia' => 0,
                        'horaAcumula' => $detalleData['horaAcumula'] ?? 0.0,
                        'aplicar' => isset($detalleData['aplicar']) ? filter_var($detalleData['aplicar'], FILTER_VALIDATE_BOOLEAN) : true,
                        'created_by' => auth()->id() ?? 1,
                    ]
                );
            }
        });
    }

    public static function normalizarHora(?string $hora): ?string
    {
        if (! $hora) {
            return null;
        }

        return strlen($hora) === 5 ? $hora.':00' : $hora;
    }

    public static function restarMinutos(string $hhmmss, int $min): string
    {
        [$h, $m, $s] = array_pad(explode(':', $hhmmss), 3, 0);
        $total = max(0, ((int) $h) * 60 + ((int) $m) - $min);

        return sprintf('%02d:%02d:%02d', intdiv($total, 60), $total % 60, (int) $s);
    }

    public static function sumarMinutos(string $hhmmss, int $min): string
    {
        [$h, $m, $s] = array_pad(explode(':', $hhmmss), 3, 0);
        $total = min(((int) $h) * 60 + ((int) $m) + $min, 23 * 60 + 59);

        return sprintf('%02d:%02d:%02d', intdiv($total, 60), $total % 60, (int) $s);
    }

    /**
     * Fuerza los segundos de un HH:MM:SS a un valor concreto.
     */
    public static function ajustarSegundos(string $hhmmss, int $segundos): string
    {
        [$h, $m] = array_pad(explode(':', $hhmmss), 3, 0);
        $segundos = max(0, min(59, $segundos));

        return sprintf('%02d:%02d:%02d', (int) $h, (int) $m, $segundos);
    }
}
