<?php

namespace App\Services\Trabajador;

use App\Models\AltasTrabajadores;
use App\Models\Auth\UsuarioPerfilIe;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Conasis\ConasisLocalesMarcacion;
use App\Models\InstitucionesEduc;
use App\Models\Personas;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistroTrabajadorService
{
    /**
     * Crea persona + trabajador + usuario (auto) + alta (opcional) + perfil IE (opcional)
     * todo en una sola transacción.
     *
     * @param  array<string, mixed>  $data
     */
    public function registrar(array $data): Trabajador
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear persona
            $persona = Personas::create([
                'pais_id' => $data['pais_id'],
                'tipoDocIdentidad_id' => $data['tipoDocIdentidad_id'],
                'docIdentidad' => $data['docIdentidad'],
                'paterno' => $data['paterno'],
                'materno' => $data['materno'],
                'nombre' => $data['nombre'],
                'sexo_id' => $data['sexo_id'],
                'fechaNac' => $data['fechaNac'] ?? null,
                'ubigeo' => $data['ubigeo'] ?? null,
                'created_by' => auth()->id() ?? 1,
                'activo' => true,
            ]);

            // 2. Crear trabajador
            $trabajador = Trabajador::create([
                'persona_id' => $persona->id,
                'created_by' => auth()->id() ?? 1,
                'activo' => true,
            ]);

            // 3. Crear usuario automáticamente (login = docIdentidad, password = docIdentidad)
            $user = User::create([
                'trabajador_id' => $trabajador->id,
                'login' => $data['docIdentidad'],
                'password' => Hash::make($data['docIdentidad']),
                'activo' => true,
            ]);

            // 4. Si se llenó información de alta, crearla
            if (! empty($data['incluir_alta']) && ! empty($data['institucionEducativa_id'])) {
                $alta = AltasTrabajadores::create([
                    'trabajador_id' => $trabajador->id,
                    'institucionEducativa_id' => $data['institucionEducativa_id'],
                    'condicionLaboral_id' => $data['condicionLaboral_id'],
                    'tipoContrato_id' => $data['tipoContrato_id'],
                    'rolTrabajador_id' => $data['rolTrabajador_id'] ?? null,
                    'situacionLaboral_id' => $data['situacionLaboral_id'],
                    'area_id' => $data['area_id'],
                    'cargo_id' => $data['cargo_id'],
                    'codigoAirsp' => $data['codigoAirsp'] ?? null,
                    'fechaInicio' => $data['fechaInicio'],
                    'fechaFin' => $data['fechaFin'] ?? null,
                    'fechaAlta' => $data['fechaAlta'] ?? now()->toDateString(),
                    'observacion' => $data['observacion'] ?? null,
                    'created_by' => auth()->id() ?? 1,
                ]);

                // 5. Si se seleccionó perfil, asignar perfil por IE
                if (! empty($data['perfil_id'])) {
                    $ugelId = InstitucionesEduc::whereKey($data['institucionEducativa_id'])->value('entidadUgel_id');

                    UsuarioPerfilIe::create([
                        'user_id' => $user->id,
                        'perfil_id' => $data['perfil_id'],
                        'entidadUgel_id' => $ugelId,
                        'institucionEducativa_id' => $data['institucionEducativa_id'],
                        'activo' => true,
                        'created_by' => auth()->id() ?? 1,
                    ]);
                }

                // 6. Si se seleccionó local de marcación, registrarlo (siempre como registro nuevo)
                if (! empty($data['localInstEduc_id'])) {
                    ConasisLocalesMarcacion::create([
                        'trabajador_id'     => $trabajador->id,
                        'altaTrabajador_id' => $alta->id,
                        'localInstEduc_id'  => $data['localInstEduc_id'],
                        'fechaInicio'       => $data['fechaInicio'],
                        'fechaFin'          => $data['fechaFin'] ?? null,
                        'created_by'        => auth()->id() ?? 1,
                    ]);
                }
            }

            return $trabajador->load('persona');
        });
    }

    /**
     * Registra múltiples trabajadores en lote, creando personas y usuarios correspondientes.
     *
     * @param  array  $rows
     * @return array
     */
    public function registrarMasivo(array $rows): array
    {
        $insertados = 0;
        $errores_validacion = [];
        $errores_db = [];

        DB::transaction(function () use ($rows, &$insertados, &$errores_validacion, &$errores_db) {
            foreach ($rows as $index => $row) {
                try {
                    $rules = [
                        'tipoDocIdentidad_id' => 'required|integer',
                        'docIdentidad' => 'required|string|max:20',
                        'paterno' => 'required|string|max:255',
                        'materno' => 'required|string|max:255',
                        'nombre' => 'required|string|max:255',
                        'sexo_id' => 'required|integer',
                        'pais_id' => 'required|integer',
                        'fechaNac' => 'nullable|date',
                        'incluir_alta' => 'nullable|boolean',
                    ];

                    if (! empty($row['incluir_alta'])) {
                        $rules['institucionEducativa_id'] = 'required|integer';
                        $rules['condicionLaboral_id'] = 'required|integer';
                        $rules['tipoContrato_id'] = 'required|integer';
                        $rules['rolTrabajador_id'] = 'nullable|integer';
                        $rules['situacionLaboral_id'] = 'required|integer';
                        $rules['area_id'] = 'required|integer';
                        $rules['cargo_id'] = 'required|integer';
                        $rules['fechaInicio'] = 'required|date';
                        $rules['fechaFin'] = 'nullable|date';
                        $rules['codigoAirsp'] = 'nullable|string|max:50';
                        $rules['observacion'] = 'nullable|string';
                        $rules['perfil_id'] = 'nullable|integer';
                        $rules['localInstEduc_id'] = 'nullable|integer';
                    }

                    $validator = validator($row, $rules);

                    if ($validator->fails()) {
                        $errores_validacion[$index] = implode(', ', $validator->errors()->all());
                        continue;
                    }

                    $doc = $row['docIdentidad'];
                    $existsPersona = Personas::where('docIdentidad', $doc)
                        ->where('tipoDocIdentidad_id', $row['tipoDocIdentidad_id'])
                        ->first();

                    if ($existsPersona) {
                        $existsTrabajador = Trabajador::where('persona_id', $existsPersona->id)
                            ->where('activo', true)
                            ->first();

                        if ($existsTrabajador) {
                            $errores_validacion[$index] = "El documento {$doc} ya está registrado como trabajador activo.";
                            continue;
                        }

                        $persona = $existsPersona;
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

                    $trabajador = Trabajador::create([
                        'persona_id' => $persona->id,
                        'created_by' => auth()->id() ?? 1,
                        'activo' => true,
                    ]);

                    $user = User::create([
                        'trabajador_id' => $trabajador->id,
                        'login' => $doc,
                        'password' => Hash::make($doc),
                        'activo' => true,
                    ]);

                    // Si se incluye alta, crearla
                    if (! empty($row['incluir_alta']) && ! empty($row['institucionEducativa_id'])) {
                        $alta = AltasTrabajadores::create([
                            'trabajador_id' => $trabajador->id,
                            'institucionEducativa_id' => $row['institucionEducativa_id'],
                            'condicionLaboral_id' => $row['condicionLaboral_id'],
                            'tipoContrato_id' => $row['tipoContrato_id'],
                            'rolTrabajador_id' => $row['rolTrabajador_id'] ?? null,
                            'situacionLaboral_id' => $row['situacionLaboral_id'],
                            'area_id' => $row['area_id'],
                            'cargo_id' => $row['cargo_id'],
                            'codigoAirsp' => $row['codigoAirsp'] ?? null,
                            'fechaInicio' => $row['fechaInicio'],
                            'fechaFin' => $row['fechaFin'] ?? null,
                            'fechaAlta' => now()->toDateString(),
                            'observacion' => $row['observacion'] ?? null,
                            'created_by' => auth()->id() ?? 1,
                        ]);

                        // Asignar perfil de usuario para esa IE si corresponde
                        if (! empty($row['perfil_id'])) {
                            $ugelIdRow = InstitucionesEduc::whereKey($row['institucionEducativa_id'])->value('entidadUgel_id');

                            UsuarioPerfilIe::create([
                                'user_id' => $user->id,
                                'perfil_id' => $row['perfil_id'],
                                'entidadUgel_id' => $ugelIdRow,
                                'institucionEducativa_id' => $row['institucionEducativa_id'],
                                'activo' => true,
                                'created_by' => auth()->id() ?? 1,
                            ]);
                        }

                        // Registrar local de marcación si pertenece a la IE (siempre registro nuevo)
                        if (! empty($row['localInstEduc_id'])) {
                            $localValido = ConasisLocalesInstEduc::where('id', $row['localInstEduc_id'])
                                ->where('institucionEduc_id', $row['institucionEducativa_id'])
                                ->exists();

                            if ($localValido) {
                                ConasisLocalesMarcacion::create([
                                    'trabajador_id'     => $trabajador->id,
                                    'altaTrabajador_id' => $alta->id,
                                    'localInstEduc_id'  => $row['localInstEduc_id'],
                                    'fechaInicio'       => $row['fechaInicio'],
                                    'fechaFin'          => $row['fechaFin'] ?? null,
                                    'created_by'        => auth()->id() ?? 1,
                                ]);
                            }
                        }
                    }

                    $insertados++;
                } catch (\Exception $e) {
                    $errores_db[] = "Fila " . ($index + 1) . ": " . $e->getMessage();
                }
            }
        });

        return [
            'insertados' => $insertados,
            'errores_validacion' => $errores_validacion,
            'errores_db' => $errores_db,
        ];
    }
}
