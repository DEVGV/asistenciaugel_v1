<?php

namespace App\Http\Controllers\Marcacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marcacion\StoreCargaMarcacionesRequest;
use App\Services\Marcacion\CargaMarcacionesService;
use Illuminate\Http\JsonResponse;

class CargaMarcacionesController extends Controller
{
    public function __construct(
        private CargaMarcacionesService $cargaService,
    ) {}

    public function store(StoreCargaMarcacionesRequest $request): JsonResponse
    {
        $archivo   = $request->file('archivo');
        $relojId   = $request->integer('reloj_id');
        $overwrite = $request->boolean('overwrite', false);

        $resultado = $this->cargaService->procesar(
            $archivo->getRealPath(),
            $relojId,
            $overwrite,
        );

        $status = $resultado['insertados'] > 0 ? 200 : 422;

        $message = $resultado['insertados'] > 0
            ? "Se registraron {$resultado['insertados']} marcaciones correctamente."
            : 'No se insertaron marcaciones.';

        if ($resultado['duplicados'] > 0) {
            $message .= " {$resultado['duplicados']} duplicadas omitidas.";
        }

        return response()->json([
            'message'    => $message,
            'insertados' => $resultado['insertados'],
            'duplicados' => $resultado['duplicados'],
            'errores'    => $resultado['errores'],
            'preview'    => $resultado['preview'],
            'resumen'    => $resultado['resumen'],
        ], $status);
    }
}
