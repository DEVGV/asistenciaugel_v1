<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Sunat\ApiPeruService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SunatController extends Controller
{
    public function __construct(
        private ApiPeruService $apiPeruService,
    ) {}

    /**
     * Consulta datos de una empresa por RUC.
     *
     * POST /api/sunat/ruc
     */
    public function ruc(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ruc' => ['required', 'string', 'size:11', 'regex:/^\d{11}$/'],
        ]);

        $result = $this->apiPeruService->consultarRuc($validated['ruc']);

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['error'] ?? 'No se encontraron datos.',
            ], 422);
        }

        return response()->json([
            'data' => $result['data'],
        ]);
    }

    /**
     * Consulta datos de una persona por DNI.
     *
     * POST /api/sunat/dni
     */
    public function dni(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dni' => ['required', 'string', 'size:8', 'regex:/^\d{8}$/'],
        ]);

        $result = $this->apiPeruService->consultarDni($validated['dni']);

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['error'] ?? 'No se encontraron datos.',
            ], 422);
        }

        return response()->json([
            'data' => $result['data'],
        ]);
    }
}
