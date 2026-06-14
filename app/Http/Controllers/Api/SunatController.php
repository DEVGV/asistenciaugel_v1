<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConsultarDniRequest;
use App\Http\Requests\Api\ConsultarRucRequest;
use App\Services\Sunat\ApiPeruService;
use Illuminate\Http\JsonResponse;

class SunatController extends Controller
{
    public function __construct(
        private ApiPeruService $apiPeruService,
    ) {}

    public function ruc(ConsultarRucRequest $request): JsonResponse
    {
        $result = $this->apiPeruService->consultarRuc($request->ruc());

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['error'] ?? 'No se encontraron datos.',
            ], 422);
        }

        return response()->json(['data' => $result['data']]);
    }

    public function dni(ConsultarDniRequest $request): JsonResponse
    {
        $result = $this->apiPeruService->consultarDni($request->dni());

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['error'] ?? 'No se encontraron datos.',
            ], 422);
        }

        return response()->json(['data' => $result['data']]);
    }
}
