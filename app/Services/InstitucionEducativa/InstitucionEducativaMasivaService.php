<?php

namespace App\Services\InstitucionEducativa;

use App\Models\InstitucionesEduc;
use Illuminate\Support\Facades\DB;

class InstitucionEducativaMasivaService
{
    /**
     * Registra múltiples Instituciones Educativas en lote.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array{insertados: int, errores_validacion: array<int, string>, errores_db: array<int, string>}
     */
    public function registrarMasivo(array $rows): array
    {
        $insertados = 0;
        $errores_validacion = [];
        $errores_db = [];

        /** Códigos ya procesados en este lote para detectar duplicados internos. */
        $codigosEnLote = [];

        DB::transaction(function () use ($rows, &$insertados, &$errores_validacion, &$errores_db, &$codigosEnLote) {
            foreach ($rows as $index => $row) {
                try {
                    $validator = validator($row, $this->reglasValidacion());

                    if ($validator->fails()) {
                        $errores_validacion[$index] = implode(', ', $validator->errors()->all());

                        continue;
                    }

                    $codigo = trim($row['codigoInstitucion']);

                    // Verificar duplicado dentro del mismo lote
                    if (in_array($codigo, $codigosEnLote, true)) {
                        $errores_validacion[$index] = "El código '{$codigo}' aparece duplicado en el lote.";

                        continue;
                    }

                    // Verificar si ya existe en la base de datos
                    if (InstitucionesEduc::where('codigoInstitucion', $codigo)->exists()) {
                        $errores_validacion[$index] = "El código de institución '{$codigo}' ya está registrado en el sistema.";

                        continue;
                    }

                    InstitucionesEduc::create([
                        'codigoInstitucion' => $codigo,
                        'codigoModular' => isset($row['codigoModular']) ? trim($row['codigoModular']) : null,
                        'nombreLegal' => trim($row['nombreLegal']),
                        'entidadUgel_id' => $row['entidadUgel_id'] ?? null,
                        'entidadAdmin_id' => $row['entidadAdmin_id'] ?? null,
                        'regimenEduc_id' => $row['regimenEduc_id'] ?? null,
                        'tipoInstEduc_id' => $row['tipoInstEduc_id'] ?? null,
                        'modalidadFormativa_id' => $row['modalidadFormativa_id'],
                        'nivelCiclo_id' => $row['nivelCiclo_id'],
                        'fechaInicio' => $row['fechaInicio'] ?? null,
                        'fechaFin' => $row['fechaFin'] ?? null,
                        'created_by' => auth()->id(),
                    ]);

                    $codigosEnLote[] = $codigo;
                    $insertados++;
                } catch (\Exception $e) {
                    $errores_db[] = 'Fila '.($index + 1).': '.$e->getMessage();
                }
            }
        });

        return [
            'insertados' => $insertados,
            'errores_validacion' => $errores_validacion,
            'errores_db' => $errores_db,
        ];
    }

    /** @return array<string, mixed> */
    private function reglasValidacion(): array
    {
        return [
            'codigoInstitucion' => 'required|string|max:30',
            'codigoModular' => 'nullable|string|max:30',
            'nombreLegal' => 'required|string|max:200',
            'entidadUgel_id' => 'nullable|integer',
            'entidadAdmin_id' => 'nullable|integer',
            'regimenEduc_id' => 'nullable|integer',
            'tipoInstEduc_id' => 'nullable|integer',
            'modalidadFormativa_id' => 'required|integer',
            'nivelCiclo_id' => 'required|integer',
            'fechaInicio' => 'nullable|date',
            'fechaFin' => 'nullable|date',
        ];
    }
}
