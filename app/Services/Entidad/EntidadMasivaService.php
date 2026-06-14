<?php

namespace App\Services\Entidad;

use App\Models\Entidades;
use Illuminate\Support\Facades\DB;

class EntidadMasivaService
{
    /**
     * Registra múltiples Entidades en lote.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array{insertados: int, errores_validacion: array<int, string>, errores_db: array<int, string>}
     */
    public function registrarMasivo(array $rows): array
    {
        $insertados = 0;
        $errores_validacion = [];
        $errores_db = [];

        /** RUCs ya procesados en este lote para detectar duplicados internos. */
        $rucsEnLote = [];

        DB::transaction(function () use ($rows, &$insertados, &$errores_validacion, &$errores_db, &$rucsEnLote) {
            foreach ($rows as $index => $row) {
                try {
                    $validator = validator($row, $this->reglasValidacion());

                    if ($validator->fails()) {
                        $errores_validacion[$index] = implode(', ', $validator->errors()->all());

                        continue;
                    }

                    $ruc = trim($row['ruc']);

                    // Verificar duplicado dentro del mismo lote
                    if (in_array($ruc, $rucsEnLote, true)) {
                        $errores_validacion[$index] = "El RUC '{$ruc}' aparece duplicado en el lote.";

                        continue;
                    }

                    // Verificar si ya existe en la base de datos
                    if (Entidades::where('ruc', $ruc)->exists()) {
                        $errores_validacion[$index] = "El RUC '{$ruc}' ya está registrado en el sistema.";

                        continue;
                    }

                    Entidades::create([
                        'tipoEntidad_id' => (int) $row['tipoEntidad_id'],
                        'ruc' => $ruc,
                        'razonSocial' => trim($row['razonSocial']),
                        'razonComercial' => isset($row['razonComercial']) ? trim($row['razonComercial']) : null,
                        'personaRepLegal_id' => isset($row['personaRepLegal_id']) ? (int) $row['personaRepLegal_id'] : null,
                        'created_by' => auth()->id() ?? 1,
                        'activo' => isset($row['activo']) ? filter_var($row['activo'], FILTER_VALIDATE_BOOLEAN) : true,
                    ]);

                    $rucsEnLote[] = $ruc;
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
            'tipoEntidad_id' => 'required|integer',
            'ruc' => 'required|string|size:11',
            'razonSocial' => 'required|string|max:255',
            'razonComercial' => 'nullable|string|max:255',
            'personaRepLegal_id' => 'nullable|integer',
            'activo' => 'nullable|boolean',
        ];
    }
}
