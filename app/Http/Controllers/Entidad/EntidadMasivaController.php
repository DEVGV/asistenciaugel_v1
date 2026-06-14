<?php

namespace App\Http\Controllers\Entidad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Entidad\StoreEntidadMasivaRequest;
use App\Services\Entidad\EntidadMasivaService;
use Illuminate\Http\JsonResponse;

class EntidadMasivaController extends Controller
{
    public function __construct(
        private EntidadMasivaService $entidadMasivaService,
    ) {}

    public function store(StoreEntidadMasivaRequest $request): JsonResponse
    {
        $resultado = $this->entidadMasivaService->registrarMasivo($request->filas());

        if ($resultado['insertados'] === 0) {
            return response()->json([
                'message' => 'No hay filas válidas para insertar.',
                'insertados' => 0,
                'errores_validacion' => $resultado['errores_validacion'],
                'errores_db' => $resultado['errores_db'],
            ], 422);
        }

        return response()->json([
            'message' => "Se insertaron {$resultado['insertados']} entidades correctamente.",
            'insertados' => $resultado['insertados'],
            'errores_validacion' => $resultado['errores_validacion'],
            'errores_db' => $resultado['errores_db'],
        ]);
    }
}
