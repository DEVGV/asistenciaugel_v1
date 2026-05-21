<?php

namespace App\DTOs\InstitucionEducativa;

final readonly class UpdateInstEducDTO
{
    public function __construct(
        public ?int $entidadUgel_id,
        public ?int $entidadAdmin_id,
        public string $codigoInstitucion,
        public ?string $codigoModular,
        public string $nombreLegal,
        public ?int $regimenEduc_id,
        public ?int $tipoInstEduc_id,
        public int $modalidadFormativa_id,
        public int $nivelCiclo_id,
        public ?string $fechaInicio,
        public ?string $fechaFin,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            entidadUgel_id: isset($data['entidadUgel_id']) ? (int) $data['entidadUgel_id'] : null,
            entidadAdmin_id: isset($data['entidadAdmin_id']) ? (int) $data['entidadAdmin_id'] : null,
            codigoInstitucion: $data['codigoInstitucion'],
            codigoModular: $data['codigoModular'] ?? null,
            nombreLegal: $data['nombreLegal'],
            regimenEduc_id: isset($data['regimenEduc_id']) ? (int) $data['regimenEduc_id'] : null,
            tipoInstEduc_id: isset($data['tipoInstEduc_id']) ? (int) $data['tipoInstEduc_id'] : null,
            modalidadFormativa_id: (int) $data['modalidadFormativa_id'],
            nivelCiclo_id: (int) $data['nivelCiclo_id'],
            fechaInicio: $data['fechaInicio'] ?? null,
            fechaFin: $data['fechaFin'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
