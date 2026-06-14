<?php

namespace App\DTOs\Horario;

final readonly class CreateCargaHorariaDTO
{
    public function __construct(
        public int $horarioCurso_id,
        public int $trabajador_id,
        public ?int $altaTrabajador_id = null,
        public ?string $fechaInicio = null,
        public ?string $fechaFin = null,
        public ?string $titularSuplencia = null,
        public ?int $created_by = null,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            horarioCurso_id: (int) $data['horarioCurso_id'],
            trabajador_id: (int) $data['trabajador_id'],
            altaTrabajador_id: isset($data['altaTrabajador_id']) ? (int) $data['altaTrabajador_id'] : null,
            fechaInicio: $data['fechaInicio'] ?? null,
            fechaFin: $data['fechaFin'] ?? null,
            titularSuplencia: $data['titularSuplencia'] ?? null,
            created_by: $data['created_by'] ?? (auth()->id() ?? 1),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
