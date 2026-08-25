<?php

namespace App\Enums;

enum AutorizadoPor: string
{
    case Director = 'D';
    case Ugel     = 'U';

    public function nombre(): string
    {
        return match ($this) {
            self::Director => 'Director',
            self::Ugel     => 'UGEL',
        };
    }
}
