<?php

namespace App\Enums;

enum EstadoTramite: string
{
    case Pendiente = 'PEN';
    case Validado = 'VAL';
    case Rechazado = 'REC';
    case Anulado = 'ANU';

    public function nombre(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Validado => 'Validado',
            self::Rechazado => 'Rechazado',
            self::Anulado => 'Anulado',
        };
    }
}
