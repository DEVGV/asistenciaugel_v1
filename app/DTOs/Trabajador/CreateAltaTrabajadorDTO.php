<?php

namespace App\DTOs\Trabajador;

final readonly class CreateAltaTrabajadorDTO
{
    public function __construct(
        public int $trabajador_id,
        public int $institucionEducativa_id,
        public int $condicionLaboral_id,
        public int $tipoContrato_id,
        public int $rolTrabajador_id,
        public int $situacionLaboral_id,
        public int $area_id,
        public int $cargo_id,
        public ?string $codigoAirsp,
        public string $fechaInicio,
        public ?string $fechaFin,
        public string $fechaAlta,
        public ?string $observacion,
        public int $created_by,
        public ?int $perfil_id = null,
        public ?int $localInstEduc_id = null,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            trabajador_id: (int) $data['trabajador_id'],
            institucionEducativa_id: (int) $data['institucionEducativa_id'],
            condicionLaboral_id: (int) $data['condicionLaboral_id'],
            tipoContrato_id: (int) $data['tipoContrato_id'],
            rolTrabajador_id: (int) $data['rolTrabajador_id'],
            situacionLaboral_id: (int) $data['situacionLaboral_id'],
            area_id: (int) $data['area_id'],
            cargo_id: (int) $data['cargo_id'],
            codigoAirsp: $data['codigoAirsp'] ?? null,
            fechaInicio: $data['fechaInicio'],
            fechaFin: $data['fechaFin'] ?? null,
            fechaAlta: $data['fechaAlta'] ?? now()->toDateString(),
            observacion: $data['observacion'] ?? null,
            created_by: auth()->id() ?? 1,
            perfil_id: isset($data['perfil_id']) ? (int) $data['perfil_id'] : null,
            localInstEduc_id: isset($data['localInstEduc_id']) ? (int) $data['localInstEduc_id'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
