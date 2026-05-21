<?php

namespace App\DTOs\Configuracion;

final readonly class UpdateCargoDTO
{
    public function __construct(
        public string $nombre,
        public ?string $abreviatura,
        public ?int $rolTrabajador_id,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            abreviatura: $data['abreviatura'] ?? null,
            rolTrabajador_id: isset($data['rolTrabajador_id']) ? (int) $data['rolTrabajador_id'] : null,
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
