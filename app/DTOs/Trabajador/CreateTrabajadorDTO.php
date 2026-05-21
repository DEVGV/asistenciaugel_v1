<?php

namespace App\DTOs\Trabajador;

final readonly class CreateTrabajadorDTO
{
    public function __construct(
        public int $persona_id,
        public int $created_by,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            persona_id: (int) $data['persona_id'],
            created_by: auth()->id() ?? 1,
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
