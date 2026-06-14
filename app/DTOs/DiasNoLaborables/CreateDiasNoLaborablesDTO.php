<?php

namespace App\DTOs\DiasNoLaborables;

final readonly class CreateDiasNoLaborablesDTO
{
    public function __construct(
        public int $institucionEduc_id,
        public string $fecha,
        public ?int $feriado_id,
        public ?string $observacion,
        public ?string $nacionalLocal,
        public ?string $recuperable,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            institucionEduc_id: (int) $data['institucionEduc_id'],
            fecha:              $data['fecha'],
            feriado_id:        isset($data['feriado_id']) ? (int) $data['feriado_id'] : null,
            observacion:       $data['observacion'] ?? null,
            nacionalLocal:     $data['nacionalLocal'] ?? null,
            recuperable:       $data['recuperable'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'institucionEduc_id' => $this->institucionEduc_id,
            'fecha'              => $this->fecha,
            'feriado_id'         => $this->feriado_id,
            'observacion'        => $this->observacion,
            'nacionalLocal'      => $this->nacionalLocal,
            'recuperable'        => $this->recuperable,
        ];
    }
}
