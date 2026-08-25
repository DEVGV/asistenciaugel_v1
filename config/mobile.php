<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuracion de la aplicacion movil
    |--------------------------------------------------------------------------
    |
    | Estos valores son consumidos por los endpoints /api/mobile/* para
    | controlar textos y enlaces externos sin recompilar la app.
    |
    */

    'permission_request' => [
        'label' => env('MOBILE_PERMISSION_REQUEST_LABEL', 'Solicitar Permiso'),
        'url' => env('MOBILE_PERMISSION_REQUEST_URL', ''),
    ],
];
