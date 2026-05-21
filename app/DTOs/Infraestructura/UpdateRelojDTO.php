<?php

namespace App\DTOs\Infraestructura;

final readonly class UpdateRelojDTO
{
    public function __construct(
        public ?string $nombre,
        public ?string $dreccionIP,
        public ?string $direccionMac,
        public ?int $puerto,
        public ?string $serie,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            nombre: $data['nombre'] ?? null,
            dreccionIP: $data['dreccionIP'] ?? null,
            direccionMac: $data['direccionMac'] ?? null,
            puerto: isset($data['puerto']) ? (int) $data['puerto'] : null,
            serie: $data['serie'] ?? null,
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
