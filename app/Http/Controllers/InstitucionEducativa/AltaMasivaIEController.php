<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreAltaMasivaIERequest;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\AltaMasivaIEService;
use Illuminate\Http\JsonResponse;

class AltaMasivaIEController extends Controller
{
    public function __construct(
        private AltaMasivaIEService $altaMasivaService,
    ) {}

    public function store(StoreAltaMasivaIERequest $request, InstitucionesEduc $institucione): JsonResponse
    {
        $resultado = $this->altaMasivaService->procesarMasivo($institucione, $request->filas());

        if ($resultado['insertados'] === 0) {
            return response()->json([
                'message'            => 'No hay filas válidas para insertar.',
                'insertados'         => 0,
                'errores_validacion' => $resultado['errores_validacion'],
                'errores_db'         => $resultado['errores_db'],
            ], 422);
        }

        return response()->json([
            'message'            => "Se insertaron {$resultado['insertados']} altas correctamente.",
            'insertados'         => $resultado['insertados'],
            'errores_validacion' => $resultado['errores_validacion'],
            'errores_db'         => $resultado['errores_db'],
        ]);
    }
}
