<?php

namespace App\DTOs\Infraestructura;

final readonly class CreateDispositivoMarcaDTO
{
    public function __construct(
        public int $telefonoMovil_id,
        public ?string $fechaInicio,
        public ?string $fechaFin,
        public int $created_by,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            telefonoMovil_id: (int) $data['telefonoMovil_id'],
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
