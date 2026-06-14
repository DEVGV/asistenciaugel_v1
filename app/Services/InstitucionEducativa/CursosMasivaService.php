<?php

namespace App\Services\InstitucionEducativa;

use App\Models\InstitucionesEduc;
use Illuminate\Support\Facades\DB;

class CursosMasivaService
{
    public function __construct(
        private CursoIEService $cursoService,
    ) {}

    /**
     * Crea cursos de forma masiva para una IE.
     *
     * @param  array<int, array<string, mixed>>  $filas
     * @return array{ creados: int, errores: array<int, string> }
     */
    public function procesarMasivo(InstitucionesEduc $ie, array $filas): array
    {
        $creados = 0;
        $errores = [];

        DB::transaction(function () use ($ie, $filas, &$creados, &$errores) {
            foreach ($filas as $idx => $fila) {
                $rowNum = $idx + 1;
                $nombre = trim($fila['nombre'] ?? '');

                if ($nombre === '') {
                    $errores[$rowNum] = 'El nombre del curso es obligatorio.';
                    continue;
                }

                try {
                    $this->cursoService->crear($ie, [
                        'nombre' => $nombre,
                        'sigla'  => trim($fila['sigla'] ?? '') ?: null,
                        'activo' => true,
                    ]);
                    $creados++;
                } catch (\Throwable $e) {
                    $errores[$rowNum] = "\"{$nombre}\": " . $e->getMessage();
                }
            }
        });

        return [
            'creados' => $creados,
            'errores' => $errores,
        ];
    }
}
