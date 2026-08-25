<?php

namespace App\DTOs\Tramite;

final readonly class UpdateExpedienteDTO
{
    public function __construct(
        public string $tipoExpediente,
        public string $asunto,
        public string $fecha,
        public ?string $observacion,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            tipoExpediente: $data['tipoExpediente'],
            asunto:         $data['asunto'],
            fecha:          $data['fecha'],
            observacion:    $data['observacion'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
