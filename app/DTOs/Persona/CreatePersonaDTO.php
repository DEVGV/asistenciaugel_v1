<?php

namespace App\DTOs\Persona;

final readonly class CreatePersonaDTO
{
    public function __construct(
        public int $tipoDocIdentidad_id,
        public string $docIdentidad,
        public string $paterno,
        public string $materno,
        public string $nombre,
        public int $sexo_id,
        public ?string $fechaNac,
        public int $pais_id,
        public ?string $ubigeo,
        public ?string $foto,
        public int $created_by,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            tipoDocIdentidad_id: (int) $data['tipoDocIdentidad_id'],
            docIdentidad: $data['docIdentidad'],
            paterno: $data['paterno'],
            materno: $data['materno'],
            nombre: $data['nombre'],
            sexo_id: (int) $data['sexo_id'],
            fechaNac: $data['fechaNac'] ?? null,
            pais_id: (int) $data['pais_id'],
            ubigeo: $data['ubigeo'] ?? null,
            foto: $data['foto'] ?? null,
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
