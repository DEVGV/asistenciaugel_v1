<?php

namespace App\DTOs\Tramite;

final readonly class CreateJustificacionDTO
{
    public function __construct(
        public ?int $motivoSuspLab_id,
        public ?int $turno,
        public string $fechaInicio,
        public string $fechaFin,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            motivoSuspLab_id: isset($data['motivoSuspLab_id']) ? (int) $data['motivoSuspLab_id'] : null,
            turno:            isset($data['turno']) ? (int) $data['turno'] : null,
            fechaInicio:      $data['fechaInicio'],
            fechaFin:         $data['fechaFin'],
            observacion:      $data['observacion'] ?? null,
        );
    }
}
