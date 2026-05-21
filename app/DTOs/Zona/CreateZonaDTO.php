<?php

namespace App\DTOs\Zona;

final readonly class CreateZonaDTO
{
    public function __construct(
        public int $tipoZona_id,
        public ?int $distrito_id,
        public string $nombre,
        public ?string $abreviatura,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            tipoZona_id: (int) $data['tipoZona_id'],
            distrito_id: isset($data['distrito_id']) ? (int) $data['distrito_id'] : null,
            nombre: $data['nombre'],
            abreviatura: $data['abreviatura'] ?? null,
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
