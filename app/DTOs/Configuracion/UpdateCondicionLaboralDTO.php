<?php

namespace App\DTOs\Configuracion;

final readonly class UpdateCondicionLaboralDTO
{
    public function __construct(
        public int $regimenLaboral_id,
        public int $tipoTrabajador_id,
        public string $nombre,
        public ?string $abreviatura,
        public ?string $descripcion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            regimenLaboral_id: (int) $data['regimenLaboral_id'],
            tipoTrabajador_id: (int) $data['tipoTrabajador_id'],
            nombre: $data['nombre'],
            abreviatura: $data['abreviatura'] ?? null,
            descripcion: $data['descripcion'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
