<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreGradosMasivaRequest;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\GradosMasivaService;
use Illuminate\Http\JsonResponse;

class GradosMasivaIEController extends Controller
{
    public function __construct(
        private GradosMasivaService $gradosMasivaService,
    ) {}

    public function store(StoreGradosMasivaRequest $request, InstitucionesEduc $institucione): JsonResponse
    {
        $resultado = $this->gradosMasivaService->procesarMasivo($institucione, $request->filas());

        if ($resultado['grados_creados'] === 0) {
            return response()->json([
                'message'           => 'No se pudo crear ningún grado.',
                'grados_creados'    => 0,
                'secciones_creadas' => 0,
                'errores'           => $resultado['errores'],
            ], 422);
        }

        return response()->json([
            'message'           => "Se crearon {$resultado['grados_creados']} grado(s) y {$resultado['secciones_creadas']} sección(es) correctamente.",
            'grados_creados'    => $resultado['grados_creados'],
            'secciones_creadas' => $resultado['secciones_creadas'],
            'errores'           => $resultado['errores'],
        ]);
    }
}
