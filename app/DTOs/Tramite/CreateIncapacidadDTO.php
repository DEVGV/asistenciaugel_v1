<?php

namespace App\DTOs\Tramite;

final readonly class CreateIncapacidadDTO
{
    public function __construct(
        public int $motivoSuspLab_id,
        public string $condicionSubsidio,
        public string $fechaInicio,
        public string $fechaFin,
        public ?float $nroDias,
        public string $nroCertificado,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            motivoSuspLab_id:  (int) $data['motivoSuspLab_id'],
            condicionSubsidio: $data['condicionSubsidio'],
            fechaInicio:       $data['fechaInicio'],
            fechaFin:          $data['fechaFin'],
            nroDias:           isset($data['nroDias']) ? (float) $data['nroDias'] : null,
            nroCertificado:    $data['nroCertificado'],
            observacion:       $data['observacion'] ?? null,
        );
    }
}
