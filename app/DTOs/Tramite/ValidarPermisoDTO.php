<?php

namespace App\DTOs\Tramite;

final readonly class ValidarPermisoDTO
{
    public function __construct(
        public string $estado,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            estado:      $data['estado'],
            observacion: $data['observacion'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
