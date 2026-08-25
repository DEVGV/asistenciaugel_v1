<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Margen de marcación (minutos)
    |--------------------------------------------------------------------------
    |
    | Minutos de margen antes de la hora de entrada y después de la hora de
    | salida en los que se permite registrar marcaciones. Por ejemplo, si el
    | margen es 30 y la clase inicia a las 08:00:
    |   - entHoraInicio = 07:30 (puede marcar desde esta hora)
    |   - entHoraFin    = 08:00 (hora real de entrada)
    |   - salHoraInicio = hora real de salida
    |   - salHoraFin    = salHoraInicio + 30 min (puede marcar hasta esta hora)
    |
    */

    'margen_marcacion' => (int) env('MARGEN_MARCACION_MINUTOS', 60),

];
