<?php

namespace App\DTOs\Infraestructura;

final readonly class UpdateLocalDTO
{
    public function __construct(
        public string $nombre,
        public ?string $domicilio,
        public ?int $zona_id,
        public ?string $ubigeo,
        public ?float $utm_huso,
        public ?string $utm_banda,
        public ?float $utm_x_este,
        public ?float $utm_y_norte,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            domicilio: $data['domicilio'] ?? null,
            zona_id: isset($data['zona_id']) ? (int) $data['zona_id'] : null,
            ubigeo: $data['ubigeo'] ?? null,
            utm_huso: isset($data['utm_huso']) ? (float) $data['utm_huso'] : null,
            utm_banda: $data['utm_banda'] ?? null,
            utm_x_este: isset($data['utm_x_este']) ? (float) $data['utm_x_este'] : null,
            utm_y_norte: isset($data['utm_y_norte']) ? (float) $data['utm_y_norte'] : null,
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
