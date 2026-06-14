<?php

namespace App\DTOs\Tramite;

final readonly class CreatePermisoDTO
{
    public function __construct(
        public int $trabajador_id,
        public int $altaTrabajador_id,
        public string $tipo,
        public string $asunto,
        public string $fechaInicio,
        public string $fechaFin,
        public ?string $marcaApli,
        public ?int $turno,
        public int $documento_id,
        public ?string $nroDoc,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            trabajador_id:     (int) $data['trabajador_id'],
            altaTrabajador_id: (int) $data['altaTrabajador_id'],
            tipo:              $data['tipo'],
            asunto:            $data['asunto'],
            fechaInicio:       $data['fechaInicio'],
            fechaFin:          $data['fechaFin'],
            marcaApli:         $data['marcaApli'] ?? null,
            turno:             isset($data['turno']) ? (int) $data['turno'] : null,
            documento_id:      (int) $data['documento_id'],
            nroDoc:            $data['nroDoc'] ?? null,
            observacion:       $data['observacion'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
