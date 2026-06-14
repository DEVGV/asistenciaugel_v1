<?php

namespace App\DTOs\Horario;

use Carbon\Carbon;

final readonly class CreateHorarioCursoDTO
{
    public function __construct(
        public int $anio,
        public int $seccion_id,
        public int $curso_id,
        public string $diaSemana,
        public int $nroDia,
        public ?int $turno_id,
        public ?string $nombreTurno,
        public string $horaInicio,
        public string $horaFin,
        public ?float $minAcum = null,
        public ?int $created_by = null,
    ) {}

    private static function resolverTurno(?int $turnoId): array
    {
        return match ($turnoId) {
            1       => [1, 'MAÑANA'],
            2       => [2, 'TARDE'],
            3       => [3, 'NOCHE'],
            default => [null, null],
        };
    }

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        $minAcum = $data['minAcum'] ?? null;
        if ($minAcum === null && ! empty($data['horaInicio']) && ! empty($data['horaFin'])) {
            $inicio = Carbon::parse($data['horaInicio']);
            $fin    = Carbon::parse($data['horaFin']);
            $minAcum = $inicio->diffInMinutes($fin);
        }

        [$turnoId, $nombreTurno] = self::resolverTurno(
            isset($data['turno_id']) ? (int) $data['turno_id'] : null
        );

        return new self(
            anio:        (int) $data['anio'],
            seccion_id:  (int) $data['seccion_id'],
            curso_id:    (int) $data['curso_id'],
            diaSemana:   (string) $data['diaSemana'],
            nroDia:      (int) $data['nroDia'],
            turno_id:    $turnoId,
            nombreTurno: $nombreTurno,
            horaInicio:  (string) $data['horaInicio'],
            horaFin:     (string) $data['horaFin'],
            minAcum:     $minAcum !== null ? (float) $minAcum : null,
            created_by:  $data['created_by'] ?? (auth()->id() ?? 1),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
