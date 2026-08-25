<?php

namespace App\Enums;

enum TipoExpediente: string
{
    case Justificacion = 'J';
    case Suspension    = 'S';
    case Exoneracion   = 'E';
    case Incapacidad   = 'I';

    public function nombre(): string
    {
        return match ($this) {
            self::Justificacion => 'Justificación',
            self::Suspension    => 'Suspensión',
            self::Exoneracion   => 'Exoneración',
            self::Incapacidad   => 'Incapacidad',
        };
    }
}
