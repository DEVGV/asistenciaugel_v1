<?php

namespace App\DTOs\Infraestructura;

final readonly class CreateLocalMarcacionDTO
{
    public function __construct(
        public int $trabajador_id,
        public ?int $altaTrabajador_id,
        public int $localInstEduc_id,
        public ?string $fechaInicio,
        public ?string $fechaFin,
        public int $created_by,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            trabajador_id: (int) $data['trabajador_id'],
            altaTrabajador_id: isset($data['altaTrabajador_id']) ? (int) $data['altaTrabajador_id'] : null,
            localInstEduc_id: (int) $data['localInstEduc_id'],
            fechaInicio: $data['fechaInicio'] ?? null,
            fechaFin: $data['fechaFin'] ?? null,
            created_by: auth()->id() ?? 1,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
