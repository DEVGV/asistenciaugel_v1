<?php

namespace App\DTOs\Tramite;

final readonly class CreateSuspensionDTO
{
    public function __construct(
        public int $motivoSuspLab_id,
        public string $fechaHoraInicio,
        public string $fechaHoraFin,
        public ?float $totalDias,
        public ?float $totalHoras,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            motivoSuspLab_id: (int) $data['motivoSuspLab_id'],
            fechaHoraInicio:  $data['fechaHoraInicio'],
            fechaHoraFin:     $data['fechaHoraFin'],
            totalDias:        isset($data['totalDias']) ? (float) $data['totalDias'] : null,
            totalHoras:       isset($data['totalHoras']) ? (float) $data['totalHoras'] : null,
            observacion:      $data['observacion'] ?? null,
        );
    }
}
