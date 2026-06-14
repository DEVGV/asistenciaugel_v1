<?php

namespace App\Http\Controllers\InstitucionEducativa;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitucionEducativa\StoreCursosMasivaRequest;
use App\Models\InstitucionesEduc;
use App\Services\InstitucionEducativa\CursosMasivaService;
use Illuminate\Http\JsonResponse;

class CursosMasivaIEController extends Controller
{
    public function __construct(
        private CursosMasivaService $cursosMasivaService,
    ) {}

    public function store(StoreCursosMasivaRequest $request, InstitucionesEduc $institucione): JsonResponse
    {
        $resultado = $this->cursosMasivaService->procesarMasivo($institucione, $request->filas());

        if ($resultado['creados'] === 0) {
            return response()->json([
                'message' => 'No se pudo crear ningún curso.',
                'creados' => 0,
                'errores' => $resultado['errores'],
            ], 422);
        }

        return response()->json([
            'message' => "Se crearon {$resultado['creados']} curso(s) correctamente.",
            'creados' => $resultado['creados'],
            'errores' => $resultado['errores'],
        ]);
    }
}
