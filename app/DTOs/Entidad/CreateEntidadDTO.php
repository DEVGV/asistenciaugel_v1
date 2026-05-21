<?php

namespace App\DTOs\Entidad;

final readonly class CreateEntidadDTO
{
    public function __construct(
        public int $tipoEntidad_id,
        public string $ruc,
        public string $razonSocial,
        public ?string $razonComercial,
        public ?int $personaRepLegal_id,
        public int $created_by,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            tipoEntidad_id: (int) $data['tipoEntidad_id'],
            ruc: $data['ruc'],
            razonSocial: $data['razonSocial'],
            razonComercial: $data['razonComercial'] ?? null,
            personaRepLegal_id: isset($data['personaRepLegal_id']) ? (int) $data['personaRepLegal_id'] : null,
            created_by: auth()->id() ?? 1, // TODO: Remove default 1 when auth is fully integrated for CLI tests
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
