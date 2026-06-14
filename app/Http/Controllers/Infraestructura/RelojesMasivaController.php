<?php

namespace App\Http\Controllers\Infraestructura;

use App\Http\Controllers\Controller;
use App\Http\Requests\Infraestructura\StoreRelojesMasivaRequest;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Services\Infraestructura\RelojesMasivaService;
use Illuminate\Http\JsonResponse;

class RelojesMasivaController extends Controller
{
    public function __construct(
        private RelojesMasivaService $relojesMasivaService,
    ) {}

    public function store(StoreRelojesMasivaRequest $request, ConasisLocalesInstEduc $localesIe): JsonResponse
    {
        $resultado = $this->relojesMasivaService->procesarMasivo($localesIe, $request->filas());

        if ($resultado['creados'] === 0) {
            return response()->json([
                'message' => 'No se pudo crear ningún reloj.',
                'creados' => 0,
                'errores' => $resultado['errores'],
            ], 422);
        }

        return response()->json([
            'message' => "Se crearon {$resultado['creados']} reloj(es) correctamente.",
            'creados' => $resultado['creados'],
            'errores' => $resultado['errores'],
        ]);
    }
}
