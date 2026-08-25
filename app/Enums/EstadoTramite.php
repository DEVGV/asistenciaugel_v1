<?php

namespace App\Enums;

enum EstadoTramite: string
{
    case Registrado = '1';
    case Aprobado = '2';
    case Rechazado = '3';
    case Autorizado = '4';
    case Anulado = '5';

    public function nombre(): string
    {
        return match ($this) {
            self::Registrado => 'Registrado',
            self::Aprobado => 'Aprobado',
            self::Rechazado => 'Rechazado',
            self::Autorizado => 'Autorizado',
            self::Anulado => 'Anulado',
        };
    }
}
