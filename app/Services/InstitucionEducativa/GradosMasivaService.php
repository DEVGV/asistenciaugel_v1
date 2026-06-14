<?php

namespace App\Services\InstitucionEducativa;

use App\Models\InstitucionesEduc;
use Illuminate\Support\Facades\DB;

class GradosMasivaService
{
    public function __construct(
        private GradoIEService $gradoService,
        private SeccionIEService $seccionService,
    ) {}

    /**
     * Crea grados y sus secciones de forma masiva para una IE.
     *
     * @param  array<int, array<string, mixed>>  $filas
     * @return array{ grados_creados: int, secciones_creadas: int, errores: array<int, string> }
     */
    public function procesarMasivo(InstitucionesEduc $ie, array $filas): array
    {
        $gradosCreados    = 0;
        $seccionesCreadas = 0;
        $errores          = [];

        DB::transaction(function () use ($ie, $filas, &$gradosCreados, &$seccionesCreadas, &$errores) {
            foreach ($filas as $idx => $fila) {
                $rowNum      = $idx + 1;
                $gradoNombre = trim($fila['grado_nombre'] ?? '');

                if ($gradoNombre === '') {
                    $errores[$rowNum] = 'El nombre del grado es obligatorio.';
                    continue;
                }

                try {
                    $grado = $this->gradoService->crear($ie, [
                        'nombre' => $gradoNombre,
                        'sigla'  => trim($fila['grado_sigla'] ?? '') ?: null,
                        'activo' => true,
                    ]);
                    $gradosCreados++;
                } catch (\Throwable $e) {
                    $errores[$rowNum] = "Grado \"{$gradoNombre}\": " . $e->getMessage();
                    continue;
                }

                foreach ($fila['secciones'] ?? [] as $secNombre) {
                    $secNombre = trim($secNombre);
                    if ($secNombre === '') {
                        continue;
                    }
                    try {
                        $this->seccionService->crear($grado, [
                            'nombre' => $secNombre,
                            'sigla'  => null,
                            'activo' => true,
                        ]);
                        $seccionesCreadas++;
                    } catch (\Throwable $e) {
                        $errores[$rowNum] = ($errores[$rowNum] ?? '')
                            . " Sección \"{$secNombre}\": " . $e->getMessage() . ';';
                    }
                }
            }
        });

        return [
            'grados_creados'    => $gradosCreados,
            'secciones_creadas' => $seccionesCreadas,
            'errores'           => $errores,
        ];
    }
}
