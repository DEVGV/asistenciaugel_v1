<?php

namespace App\DTOs\Configuracion;

final readonly class CreateCondicionLaboralDTO
{
    public function __construct(
        public int $regimenLaboral_id,
        public int $tipoTrabajador_id,
        public string $nombre,
        public ?string $abreviatura,
        public ?string $descripcion,
        public int $created_by,
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
            created_by: auth()->id() ?? 1,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
