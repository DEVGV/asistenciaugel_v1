<?php

namespace App\DTOs\Tramite;

final readonly class CreateDocumentoTramDTO
{
    public function __construct(
        public int $documento_id,
        public ?string $nroDoc,
        public ?string $fechaDoc,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            documento_id: (int) $data['documento_id'],
            nroDoc:       $data['nroDoc'] ?? null,
            fechaDoc:     $data['fechaDoc'] ?? null,
            observacion:  $data['observacion'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
