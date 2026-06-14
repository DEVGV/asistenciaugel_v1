<?php

namespace App\Enums;

/**
 * Tipo de solicitud de permiso.
 *
 * - Justificacion: permiso por uno o varios días (se registra en conasis.t_justificaciones)
 * - Exoneracion:   exoneración de marca de entrada y/o salida (conasis.t_exoneracionesMarcacion)
 */
enum TipoPermiso: string
{
    case Justificacion = 'J';
    case Exoneracion = 'E';
}
