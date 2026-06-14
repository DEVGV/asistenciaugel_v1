<?php

namespace App\DTOs\Horario;

final readonly class CreateHorarioTrabajadorDTO
{
    public function __construct(
        public int $anio,
        public int $institucionEduc_id,
        public int $trabajador_id,
        public ?int $altaTrabajador_id = null,
        public ?string $tipoHorario = null,
        public ?string $nombre = null,
        public ?string $fechaInicio = null,
        public ?string $fechaFin = null,
        public ?int $created_by = null,
        public bool $archivado = false,
        public bool $activo = true,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            anio: (int) $data['anio'],
            institucionEduc_id: (int) $data['institucionEduc_id'],
            trabajador_id: (int) $data['trabajador_id'],
            altaTrabajador_id: isset($data['altaTrabajador_id']) ? (int) $data['altaTrabajador_id'] : null,
            tipoHorario: $data['tipoHorario'] ?? null,
            nombre: $data['nombre'] ?? null,
            fechaInicio: $data['fechaInicio'] ?? null,
            fechaFin: $data['fechaFin'] ?? null,
            created_by: $data['created_by'] ?? (auth()->id() ?? 1),
            archivado: isset($data['archivado']) ? filter_var($data['archivado'], FILTER_VALIDATE_BOOLEAN) : false,
            activo: isset($data['activo']) ? filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN) : true,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
