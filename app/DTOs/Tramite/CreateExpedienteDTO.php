<?php

namespace App\DTOs\Tramite;

final readonly class CreateExpedienteDTO
{
    /**
     * @param array<int, CreateDocumentoTramDTO> $documentos
     */
    public function __construct(
        public string $tipoExpediente,
        public int $trabajador_id,
        public ?int $altaTrabajador_id,
        public string $asunto,
        public string $fecha,
        public ?string $observacion,
        public array $documentos,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        $documentos = array_map(
            fn (array $d) => CreateDocumentoTramDTO::from($d),
            $data['documentos'] ?? [],
        );

        return new self(
            tipoExpediente:    $data['tipoExpediente'],
            trabajador_id:     (int) $data['trabajador_id'],
            altaTrabajador_id: isset($data['altaTrabajador_id']) ? (int) $data['altaTrabajador_id'] : null,
            asunto:            $data['asunto'],
            fecha:             $data['fecha'],
            observacion:       $data['observacion'] ?? null,
            documentos:        $documentos,
        );
    }
}
