<?php

namespace App\DTOs\Infraestructura;

final readonly class CreateLocalInstEducDTO
{
    public function __construct(
        public int $local_id,
        public ?int $entidad_id,
        public int $institucionEduc_id,
        public ?string $fechaInicio,
        public ?string $fechaFin,
        public int $created_by,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            local_id: (int) $data['local_id'],
            entidad_id: isset($data['entidad_id']) ? (int) $data['entidad_id'] : null,
            institucionEduc_id: (int) $data['institucionEduc_id'],
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
