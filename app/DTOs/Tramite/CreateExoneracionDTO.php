<?php

namespace App\DTOs\Tramite;

final readonly class CreateExoneracionDTO
{
    public function __construct(
        public ?int $motivoSuspLab_id,
        public string $fechaInicio,
        public string $fechaFin,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            motivoSuspLab_id: isset($data['motivoSuspLab_id']) ? (int) $data['motivoSuspLab_id'] : null,
            fechaInicio:      $data['fechaInicio'],
            fechaFin:         $data['fechaFin'],
            observacion:      $data['observacion'] ?? null,
        );
    }
}
