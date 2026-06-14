<?php

namespace App\DTOs\Auth;

final readonly class SeleccionarContextoDTO
{
    public function __construct(
        public int $ugel_id,
        public ?int $ie_id,
    ) {}

    /** @param array<string, mixed> $data */
    public static function from(array $data): self
    {
        return new self(
            ugel_id: (int) $data['ugel_id'],
            ie_id: isset($data['ie_id']) ? (int) $data['ie_id'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
