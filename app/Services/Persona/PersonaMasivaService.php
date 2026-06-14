<?php

namespace App\Services\Persona;

use App\Models\Personas;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonaMasivaService
{
    /**
     * Registra múltiples personas en lote, creando opcionalmente trabajador y usuario.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array{insertados: int, errores_validacion: array<int, string>, errores_db: array<int, string>}
     */
    public function registrarMasivo(array $rows): array
    {
        $insertados = 0;
        $errores_validacion = [];
        $errores_db = [];

        DB::transaction(function () use ($rows, &$insertados, &$errores_validacion, &$errores_db) {
            foreach ($rows as $index => $row) {
                try {
                    $validator = validator($row, $this->reglasValidacion());

                    if ($validator->fails()) {
                        $errores_validacion[$index] = implode(', ', $validator->errors()->all());

                        continue;
                    }

                    $doc = trim($row['docIdentidad']);

                    // Verificar si ya existe la persona
                    $persona = Personas::where('docIdentidad', $doc)
                        ->where('tipoDocIdentidad_id', $row['tipoDocIdentidad_id'])
                        ->first();

                    if ($persona) {
                        // Si ya existe y se quiere marcar como trabajador, verificar que no lo sea ya
                        if (! empty($row['es_trabajador'])) {
                            $tieneTrabajadorActivo = Trabajador::where('persona_id', $persona->id)
                                ->where('activo', true)
                                ->exists();

                            if ($tieneTrabajadorActivo) {
                                $errores_validacion[$index] = "El documento {$doc} ya está registrado como trabajador activo.";

                                continue;
                            }
                        } else {
                            $errores_validacion[$index] = "El documento {$doc} ya está registrado en el sistema.";

                            continue;
                        }
                    } else {
                        $persona = Personas::create([
                            'pais_id' => $row['pais_id'],
                            'tipoDocIdentidad_id' => $row['tipoDocIdentidad_id'],
                            'docIdentidad' => $doc,
                            'paterno' => $row['paterno'],
                            'materno' => $row['materno'],
                            'nombre' => $row['nombre'],
                            'sexo_id' => $row['sexo_id'],
                            'fechaNac' => $row['fechaNac'] ?? null,
                            'created_by' => auth()->id() ?? 1,
                            'activo' => true,
                        ]);
                    }

                    // Crear trabajador y usuario si se solicitó
                    if (! empty($row['es_trabajador'])) {
                        $trabajador = Trabajador::where('persona_id', $persona->id)->first();

                        if ($trabajador) {
                            $trabajador->update(['activo' => true]);
                        } else {
                            $trabajador = Trabajador::create([
                                'persona_id' => $persona->id,
                                'created_by' => auth()->id() ?? 1,
                                'activo' => true,
                            ]);
                        }

                        $user = $trabajador->user ?? null;

                        if ($user) {
                            $user->update(['activo' => true]);
                        } else {
                            User::create([
                                'trabajador_id' => $trabajador->id,
                                'login' => $doc,
                                'password' => Hash::make($doc),
                                'activo' => true,
                            ]);
                        }
                    }

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
            'tipoDocIdentidad_id' => 'required|integer',
            'docIdentidad' => 'required|string|max:20',
            'paterno' => 'required|string|max:100',
            'materno' => 'required|string|max:100',
            'nombre' => 'required|string|max:100',
            'sexo_id' => 'required|integer',
            'pais_id' => 'required|integer',
            'fechaNac' => 'nullable|date',
            'es_trabajador' => 'nullable|boolean',
        ];
    }
}
