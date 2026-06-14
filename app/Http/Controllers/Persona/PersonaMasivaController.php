<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Http\Requests\Persona\StorePersonaMasivaRequest;
use App\Services\Persona\PersonaMasivaService;
use Illuminate\Http\JsonResponse;

class PersonaMasivaController extends Controller
{
    public function __construct(
        private PersonaMasivaService $personaMasivaService,
    ) {}

    public function store(StorePersonaMasivaRequest $request): JsonResponse
    {
        $resultado = $this->personaMasivaService->registrarMasivo($request->filas());

        if ($resultado['insertados'] === 0) {
            return response()->json([
                'message' => 'No hay filas válidas para insertar.',
                'insertados' => 0,
                'errores_validacion' => $resultado['errores_validacion'],
                'errores_db' => $resultado['errores_db'],
            ], 422);
        }

        return response()->json([
            'message' => "Se insertaron {$resultado['insertados']} personas correctamente.",
            'insertados' => $resultado['insertados'],
            'errores_validacion' => $resultado['errores_validacion'],
            'errores_db' => $resultado['errores_db'],
        ]);
    }
}
