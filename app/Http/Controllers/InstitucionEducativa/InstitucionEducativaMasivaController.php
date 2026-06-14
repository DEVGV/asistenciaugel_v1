<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreInstEducMasivaRequest;
use App\Services\InstitucionEducativa\InstitucionEducativaMasivaService;
use Illuminate\Http\JsonResponse;

class InstitucionEducativaMasivaController extends Controller
{
    public function __construct(
        private InstitucionEducativaMasivaService $instEducMasivaService,
    ) {}

    public function store(StoreInstEducMasivaRequest $request): JsonResponse
    {
        $resultado = $this->instEducMasivaService->registrarMasivo($request->filas());

        if ($resultado['insertados'] === 0) {
            return response()->json([
                'message' => 'No hay filas válidas para insertar.',
                'insertados' => 0,
                'errores_validacion' => $resultado['errores_validacion'],
                'errores_db' => $resultado['errores_db'],
            ], 422);
        }

        return response()->json([
            'message' => "Se insertaron {$resultado['insertados']} instituciones educativas correctamente.",
            'insertados' => $resultado['insertados'],
            'errores_validacion' => $resultado['errores_validacion'],
            'errores_db' => $resultado['errores_db'],
        ]);
    }
}
