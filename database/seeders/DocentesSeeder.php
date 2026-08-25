<?php

namespace Database\Seeders;

use App\Models\AltasTrabajadores;
use App\Models\Auth\Perfil;
use App\Models\Auth\UsuarioPerfilIe;
use App\Models\Cargos;
use App\Models\CondicionesLaborales;
use App\Models\Emails;
use App\Models\InstitucionesEduc;
use App\Models\Personas;
use App\Models\Telefonos;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DocentesSeeder extends Seeder
{
    public function run(): void
    {
        $ieId = 1;
        $ie = InstitucionesEduc::findOrFail($ieId);
        $ugelId = $ie->entidadUgel_id;
        $perfilDocente = Perfil::where('nombre', 'Docente')->firstOrFail();
        $areaGeneralId = 1;
        $paisPeruId = 193;
        $tipoDocDniId = 1;
        $situacionActivoId = 2;

        // --- Crear condiciones laborales faltantes ---
        $condiciones = $this->crearCondicionesLaborales();

        // --- Crear cargos faltantes ---
        $cargos = $this->crearCargos();

        // --- Datos de los docentes ---
        $docentes = $this->getDocentes();

        $insertados = 0;
        $errores = [];

        DB::transaction(function () use (
            $docentes, $condiciones, $cargos, $ieId, $ugelId, $perfilDocente,
            $areaGeneralId, $paisPeruId, $tipoDocDniId, $situacionActivoId,
            &$insertados, &$errores
        ) {
            foreach ($docentes as $i => $d) {
                try {
                    $dni = $d['dni'];

                    $existsPersona = Personas::where('docIdentidad', $dni)
                        ->where('tipoDocIdentidad_id', $tipoDocDniId)
                        ->first();

                    if ($existsPersona) {
                        $existsTrabajador = Trabajador::where('persona_id', $existsPersona->id)
                            ->where('activo', true)
                            ->first();
                        if ($existsTrabajador) {
                            $errores[] = "Fila {$d['num']}: DNI {$dni} ya existe como trabajador activo, omitido.";
                            continue;
                        }
                    }

                    $persona = $existsPersona ?? Personas::create([
                        'pais_id' => $paisPeruId,
                        'tipoDocIdentidad_id' => $tipoDocDniId,
                        'docIdentidad' => $dni,
                        'paterno' => mb_strtoupper($d['paterno']),
                        'materno' => mb_strtoupper($d['materno']),
                        'nombre' => mb_strtoupper($d['nombre']),
                        'sexo_id' => $d['sexo_id'],
                        'fechaNac' => $d['fechaNac'],
                        'created_by' => 1,
                        'activo' => true,
                    ]);

                    $trabajador = Trabajador::create([
                        'persona_id' => $persona->id,
                        'created_by' => 1,
                        'activo' => true,
                    ]);

                    $existsUser = User::where('login', $dni)->first();
                    if ($existsUser) {
                        $errores[] = "Fila {$d['num']}: Login {$dni} ya existe, omitido crear usuario.";
                        continue;
                    }

                    $user = User::create([
                        'trabajador_id' => $trabajador->id,
                        'login' => $dni,
                        'password' => Hash::make($dni),
                        'activo' => true,
                    ]);

                    $condicionId = $condiciones[$d['condicion']];
                    $cargoId = $cargos[$d['cargo_key']];
                    $tipoContratoId = $this->getTipoContratoId($d['condicion']);
                    $rolTrabajadorId = $d['rolTrabajador_id'];

                    AltasTrabajadores::create([
                        'trabajador_id' => $trabajador->id,
                        'institucionEducativa_id' => $ieId,
                        'condicionLaboral_id' => $condicionId,
                        'tipoContrato_id' => $tipoContratoId,
                        'rolTrabajador_id' => $rolTrabajadorId,
                        'situacionLaboral_id' => $situacionActivoId,
                        'area_id' => $areaGeneralId,
                        'cargo_id' => $cargoId,
                        'fechaInicio' => now()->toDateString(),
                        'fechaAlta' => now()->toDateString(),
                        'observacion' => $d['especialidad'] ? 'Especialidad: ' . mb_strtoupper($d['especialidad']) : null,
                        'created_by' => 1,
                    ]);

                    UsuarioPerfilIe::create([
                        'user_id' => $user->id,
                        'perfil_id' => $perfilDocente->id,
                        'entidadUgel_id' => $ugelId,
                        'institucionEducativa_id' => $ieId,
                        'activo' => true,
                        'created_by' => 1,
                    ]);

                    if (!empty($d['email'])) {
                        Emails::create([
                            'persona_id' => $persona->id,
                            'email' => $d['email'],
                            'personalInst' => 'P',
                            'fechaInicio' => now()->toDateString(),
                            'created_by' => 1,
                        ]);
                    }

                    if (!empty($d['celular'])) {
                        Telefonos::create([
                            'persona_id' => $persona->id,
                            'operador_id' => 2,
                            'movilFijo' => 'M',
                            'codigoPais' => '51',
                            'numero' => $d['celular'],
                            'fechaInicio' => now()->toDateString(),
                            'created_by' => 1,
                        ]);
                    }

                    $insertados++;
                } catch (\Exception $e) {
                    $errores[] = "Fila {$d['num']}: " . $e->getMessage();
                }
            }
        });

        $this->command->info("Insertados: {$insertados}");
        if (count($errores) > 0) {
            $this->command->warn("Errores:");
            foreach ($errores as $err) {
                $this->command->warn("  - {$err}");
            }
        }
    }

    private function crearCondicionesLaborales(): array
    {
        $regimenMagisterio = 23;
        $tipoTrabMagisterio = 40;

        $condMap = [];
        $defs = [
            'NOMBRADO' => ['nombre' => 'NOMBRADO', 'abreviatura' => 'NOMB'],
            'CONTRATADO' => ['nombre' => 'CONTRATADO', 'abreviatura' => 'CONT'],
            'DESIGNADO' => ['nombre' => 'DESIGNADO', 'abreviatura' => 'DESIG'],
            'DESTACADO' => ['nombre' => 'DESTACADO', 'abreviatura' => 'DEST'],
            'REASIGNADO' => ['nombre' => 'REASIGNADO', 'abreviatura' => 'REAS'],
            'PERSONAL JERÁRQUICO' => ['nombre' => 'PERSONAL JERÁRQUICO', 'abreviatura' => 'PJERQ'],
        ];

        foreach ($defs as $key => $def) {
            $existing = CondicionesLaborales::where('nombre', $def['nombre'])->first();
            if ($existing) {
                $condMap[$key] = $existing->id;
            } else {
                $cond = CondicionesLaborales::create([
                    'regimenLaboral_id' => $regimenMagisterio,
                    'tipoTrabajador_id' => $tipoTrabMagisterio,
                    'nombre' => $def['nombre'],
                    'abreviatura' => $def['abreviatura'],
                    'created_by' => 1,
                ]);
                $condMap[$key] = $cond->id;
            }
        }

        return $condMap;
    }

    private function crearCargos(): array
    {
        $cargoMap = [];

        $cargoMap['DOCENTE'] = 1;

        $defs = [
            'SUB DIRECTOR' => ['nombre' => 'SUB DIRECTOR', 'abreviatura' => 'SDIR', 'rolTrabajador_id' => 3],
            'AUX. DE EDUCACIÓN' => ['nombre' => 'AUXILIAR DE EDUCACIÓN', 'abreviatura' => 'AUXE', 'rolTrabajador_id' => 4],
            'AUX. DE SERVICIO' => ['nombre' => 'AUXILIAR DE SERVICIO', 'abreviatura' => 'AUXS', 'rolTrabajador_id' => 1],
            'SECRETARIA' => ['nombre' => 'SECRETARIA', 'abreviatura' => 'SEC', 'rolTrabajador_id' => 1],
            'PSICÓLOGO/A' => ['nombre' => 'PSICÓLOGO/A', 'abreviatura' => 'PSIC', 'rolTrabajador_id' => 1],
            'COORDINADOR DE TUTORÍA' => ['nombre' => 'COORDINADOR DE TUTORÍA', 'abreviatura' => 'CTUT', 'rolTrabajador_id' => 2],
            'TEC. ADMINISTRATIVO' => ['nombre' => 'TÉCNICO ADMINISTRATIVO', 'abreviatura' => 'TADM', 'rolTrabajador_id' => 1],
            'VIGILANCIA' => ['nombre' => 'VIGILANCIA', 'abreviatura' => 'VIG', 'rolTrabajador_id' => 1],
            'PIP' => ['nombre' => 'PIP', 'abreviatura' => 'PIP', 'rolTrabajador_id' => 1],
        ];

        foreach ($defs as $key => $def) {
            $existing = Cargos::where('nombre', $def['nombre'])->first();
            if ($existing) {
                $cargoMap[$key] = $existing->id;
            } else {
                $cargo = Cargos::create([
                    'nombre' => $def['nombre'],
                    'abreviatura' => $def['abreviatura'],
                    'rolTrabajador_id' => $def['rolTrabajador_id'],
                    'created_by' => 1,
                    'activo' => true,
                ]);
                $cargoMap[$key] = $cargo->id;
            }
        }

        return $cargoMap;
    }

    private function getTipoContratoId(string $condicion): int
    {
        return match ($condicion) {
            'NOMBRADO', 'DESIGNADO', 'REASIGNADO', 'PERSONAL JERÁRQUICO' => 24,
            'CONTRATADO', 'DESTACADO' => 25,
            default => 25,
        };
    }

    private function normalizarCondicion(string $raw): string
    {
        $raw = mb_strtoupper(trim($raw));
        return match (true) {
            str_contains($raw, 'NOMBRAD') => 'NOMBRADO',
            str_contains($raw, 'CONTRAT') => 'CONTRATADO',
            str_contains($raw, 'DESIGN') => 'DESIGNADO',
            str_contains($raw, 'DESTAC') => 'DESTACADO',
            str_contains($raw, 'REASIGN') => 'REASIGNADO',
            str_contains($raw, 'JERÁRQ') || str_contains($raw, 'JERARQ') => 'PERSONAL JERÁRQUICO',
            default => 'CONTRATADO',
        };
    }

    private function normalizarCargo(string $raw): array
    {
        $raw = mb_strtoupper(trim($raw));
        return match (true) {
            str_contains($raw, 'PROF') => ['cargo_key' => 'DOCENTE', 'rolTrabajador_id' => 2],
            str_contains($raw, 'SUB DIRECTOR') || str_contains($raw, 'SUBDIRECTOR') => ['cargo_key' => 'SUB DIRECTOR', 'rolTrabajador_id' => 3],
            str_contains($raw, 'AUXILIAR DE EDUC') || str_contains($raw, 'AUX') && str_contains($raw, 'EDUC') => ['cargo_key' => 'AUX. DE EDUCACIÓN', 'rolTrabajador_id' => 4],
            str_contains($raw, 'AUX') && str_contains($raw, 'SERV') => ['cargo_key' => 'AUX. DE SERVICIO', 'rolTrabajador_id' => 1],
            str_contains($raw, 'SECRETAR') => ['cargo_key' => 'SECRETARIA', 'rolTrabajador_id' => 1],
            str_contains($raw, 'PSICOL') => ['cargo_key' => 'PSICÓLOGO/A', 'rolTrabajador_id' => 1],
            str_contains($raw, 'COORDINADOR') || str_contains($raw, 'COORD') => ['cargo_key' => 'COORDINADOR DE TUTORÍA', 'rolTrabajador_id' => 2],
            str_contains($raw, 'TEC') && str_contains($raw, 'ADM') => ['cargo_key' => 'TEC. ADMINISTRATIVO', 'rolTrabajador_id' => 1],
            str_contains($raw, 'VIGILANC') => ['cargo_key' => 'VIGILANCIA', 'rolTrabajador_id' => 1],
            str_contains($raw, 'PIP') => ['cargo_key' => 'PIP', 'rolTrabajador_id' => 1],
            default => ['cargo_key' => 'DOCENTE', 'rolTrabajador_id' => 2],
        };
    }

    private function getDocentes(): array
    {
        $raw = [
            ['num' => 1, 'nombre_completo' => 'Bautista Llerena, Sixto Rubén', 'cargo' => 'PROF. HORAS', 'especialidad' => 'MATEMATICAS', 'dni' => '26631003', 'condicion' => 'NOMBRADO', 'fechaNac' => '1962-04-11', 'celular' => '976722730', 'email' => 'sixto11.bautista@gmail.com', 'sexo_id' => 1],
            ['num' => 2, 'nombre_completo' => 'Moya Zavaleta, Marcial', 'cargo' => 'PROF. HORAS', 'especialidad' => 'CC.NN. BIOLOG.', 'dni' => '26619564', 'condicion' => 'NOMBRADO', 'fechaNac' => '1962-04-16', 'celular' => '975101938', 'email' => 'moya16za@gmail.com', 'sexo_id' => 1],
            ['num' => 3, 'nombre_completo' => 'Terrones Mondragón, Redin', 'cargo' => 'PROF. HORAS', 'especialidad' => 'ARTE Y CULTURA', 'dni' => '41039240', 'condicion' => 'CONTRATADO', 'fechaNac' => '1981-04-01', 'celular' => '976628530', 'email' => 'redy_777@outlook.com', 'sexo_id' => 1],
            ['num' => 4, 'nombre_completo' => 'ABANTO BRICEÑO, Evelyn Gema', 'cargo' => 'AUX DE SERVICIO III', 'especialidad' => 'AE', 'dni' => '41864236', 'condicion' => 'NOMBRADO', 'fechaNac' => '1982-04-30', 'celular' => '928002101', 'email' => 'zzz55350@gmail.com', 'sexo_id' => 2],
            ['num' => 5, 'nombre_completo' => 'QUIROZ ALVAREZ, María del Pilar', 'cargo' => 'SECRETARIA II', 'especialidad' => 'TE', 'dni' => '26663501', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-08-05', 'celular' => '949227212', 'email' => 'mapiquial@gmail.com', 'sexo_id' => 2],
            ['num' => 6, 'nombre_completo' => 'RUIZ BRIONES, Carlos Nicanor', 'cargo' => 'SUB DIRECTOR  RECOLETA', 'especialidad' => 'BIOLOGIA QUIMICA', 'dni' => '19324166', 'condicion' => 'DESIGNADO', 'fechaNac' => '1972-08-08', 'celular' => '976550781', 'email' => 'carlosnrb@hotmail.com', 'sexo_id' => 1],
            ['num' => 7, 'nombre_completo' => 'CALUA TIRADO, Judith', 'cargo' => 'PSICOLOGA', 'especialidad' => 'PSICOLOGA', 'dni' => '26717321', 'condicion' => 'CONTRATADO', 'fechaNac' => '1980-08-30', 'celular' => null, 'email' => 'judcati308@gmail.com', 'sexo_id' => 2],
            ['num' => 8, 'nombre_completo' => 'Campos Requelme, Gilberto', 'cargo' => 'PROF. HORAS', 'especialidad' => 'EDUC. FISICA', 'dni' => '27557357', 'condicion' => 'CONTRATADO', 'fechaNac' => '1964-08-02', 'celular' => '962870779', 'email' => 'gilbertocr.sr@gmail.com', 'sexo_id' => 1],
            ['num' => 9, 'nombre_completo' => 'Saldaña Alvarado De Torrel, Mirtha Abigail', 'cargo' => 'PROF. HORAS', 'especialidad' => 'LENGUA Y LITERATURA', 'dni' => '26694466', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-08-25', 'celular' => '976768822', 'email' => 'mirtha320@hotmail.com', 'sexo_id' => 2],
            ['num' => 10, 'nombre_completo' => 'Aguilar Ydrogo, Santiago', 'cargo' => 'PROF. HORAS', 'especialidad' => 'IDIOMA EXTRANJERO', 'dni' => '26689583', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-08-18', 'celular' => '995361218', 'email' => 'saguilary6517@gmail.com', 'sexo_id' => 1],
            ['num' => 11, 'nombre_completo' => 'Arana Davila, Iris Virginia', 'cargo' => 'PROF. HORAS', 'especialidad' => 'LENGUA Y LITERATURA', 'dni' => '26704270', 'condicion' => 'NOMBRADO', 'fechaNac' => '1974-12-18', 'celular' => '971821882', 'email' => 'irisanana7@gmail.com', 'sexo_id' => 2],
            ['num' => 12, 'nombre_completo' => 'Gutierrez Rojas, Nelbi Yaqueliny', 'cargo' => 'PROF. HORAS', 'especialidad' => 'RELIGIÓN', 'dni' => '26687812', 'condicion' => 'CONTRATADO', 'fechaNac' => '1968-12-19', 'celular' => '952448088', 'email' => 'nelbi.gutierrez@gmail.com', 'sexo_id' => 2],
            ['num' => 13, 'nombre_completo' => 'Iberico Deza, Katia', 'cargo' => 'PROF. HORAS', 'especialidad' => 'INGLES', 'dni' => '26719777', 'condicion' => 'CONTRATADO', 'fechaNac' => '1976-12-06', 'celular' => '944240695', 'email' => 'kibericod@gmail.com', 'sexo_id' => 2],
            ['num' => 14, 'nombre_completo' => 'Linares Vasquez, Pedro Omar', 'cargo' => 'PROF. HORAS', 'especialidad' => 'HISTORIA Y GEOGRAFIA', 'dni' => '42804420', 'condicion' => 'NOMBRADO', 'fechaNac' => '1980-12-28', 'celular' => '942145577', 'email' => 'omaliva@gmail.com', 'sexo_id' => 1],
            ['num' => 15, 'nombre_completo' => 'Salazar Salazar, Wilfredo', 'cargo' => 'PROF. HORAS', 'especialidad' => 'EDUCACION FISICA', 'dni' => '26622331', 'condicion' => 'NOMBRADO', 'fechaNac' => '1963-12-17', 'celular' => '976028621', 'email' => 'wisasa1963@hotmail.com', 'sexo_id' => 1],
            ['num' => 16, 'nombre_completo' => 'ABANTO SANCHEZ, Jesús Emperatriz', 'cargo' => 'AUX DE SERVICIO III', 'especialidad' => 'AE', 'dni' => '26691284', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-12-08', 'celular' => '965035814', 'email' => 'jesusabantosanchez@gmail.com', 'sexo_id' => 2],
            ['num' => 17, 'nombre_completo' => 'YANGUA DE ABANTO, Flora', 'cargo' => 'AUX DE SERVICIO III', 'especialidad' => 'AE', 'dni' => '26630888', 'condicion' => 'NOMBRADO', 'fechaNac' => '1962-12-22', 'celular' => '968305437', 'email' => 'flor-yangua@hotmail.com', 'sexo_id' => 2],
            ['num' => 18, 'nombre_completo' => 'Tello Acosta, María Isabel', 'cargo' => 'PROF. HORAS', 'especialidad' => 'EDUCACIÓN FÍSICA', 'dni' => '26610113', 'condicion' => 'NOMBRADO', 'fechaNac' => '1963-01-24', 'celular' => '956970086', 'email' => 'marite_acos@hotmail.com', 'sexo_id' => 2],
            ['num' => 19, 'nombre_completo' => 'Asencio Malaga, Jorge Luis', 'cargo' => 'PROF. HORAS', 'especialidad' => 'CC.SS.', 'dni' => '02606018', 'condicion' => 'CONTRATADO', 'fechaNac' => '1975-02-05', 'celular' => '948748269', 'email' => 'malagajorge2020@gmail.com', 'sexo_id' => 1],
            ['num' => 20, 'nombre_completo' => 'Apaza Onque, Marco Antonio', 'cargo' => 'PROF. HORAS', 'especialidad' => 'INGLES', 'dni' => '29597591', 'condicion' => 'CONTRATADO', 'fechaNac' => '1973-02-25', 'celular' => '939890536', 'email' => 'marpaz_21@hotmail.com', 'sexo_id' => 1],
            ['num' => 21, 'nombre_completo' => 'Gutierrez Marin, Carmen Nelida', 'cargo' => 'PROF. HORAS', 'especialidad' => 'MATEMATICAS', 'dni' => '26710563', 'condicion' => 'CONTRATADO', 'fechaNac' => '1976-02-23', 'celular' => '950812050', 'email' => 'gmkrmenn11@gmail.com', 'sexo_id' => 2],
            ['num' => 22, 'nombre_completo' => 'Morales Goicochea, Juan Alberto', 'cargo' => 'PROF. HORAS', 'especialidad' => 'MATEMÁTICA', 'dni' => '26615979', 'condicion' => 'NOMBRADO', 'fechaNac' => '1961-02-01', 'celular' => '94944877', 'email' => 'albert_moralesg@hotmail.com', 'sexo_id' => 1],
            ['num' => 23, 'nombre_completo' => 'More Muñoz, Luis Efrain', 'cargo' => 'PROF. HORAS', 'especialidad' => 'CYT', 'dni' => '26698650', 'condicion' => 'CONTRATADO', 'fechaNac' => '1974-02-28', 'celular' => '976425536', 'email' => 'efmorem@gmail.com', 'sexo_id' => 1],
            ['num' => 24, 'nombre_completo' => 'Sáenz Casanova, Nelly Marleny', 'cargo' => 'PROF. HORAS', 'especialidad' => 'MATEMÁTICA', 'dni' => '26728887', 'condicion' => 'NOMBRADO', 'fechaNac' => '1973-01-12', 'celular' => '920056351', 'email' => 'saenzcasanovanely@hotmail.com', 'sexo_id' => 2],
            ['num' => 25, 'nombre_completo' => 'Ascencio Del Campo, Julia del Socorro', 'cargo' => 'PROF. HORAS', 'especialidad' => 'ARTISTICA MUSICA', 'dni' => '26641698', 'condicion' => 'NOMBRADO', 'fechaNac' => '1972-07-31', 'celular' => '976004445', 'email' => 'socoasdelca@gmail.com', 'sexo_id' => 2],
            ['num' => 26, 'nombre_completo' => 'Mendo Cieza, Liliana Concepción', 'cargo' => 'PROF. HORAS', 'especialidad' => 'CYT', 'dni' => '42497745', 'condicion' => 'DESTACADO', 'fechaNac' => '1984-07-26', 'celular' => '988931164', 'email' => 'lina.jhely@gmail.com', 'sexo_id' => 2],
            ['num' => 27, 'nombre_completo' => 'Noriega Pizarro, Benjamín', 'cargo' => 'PROF. HORAS', 'especialidad' => 'DPCC', 'dni' => '26655196', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-07-29', 'celular' => '981794080', 'email' => 'noriega2930@hotmail.com', 'sexo_id' => 1],
            ['num' => 28, 'nombre_completo' => 'Rodriguez Atalaya, Maritza Ysabel', 'cargo' => 'PROF. HORAS', 'especialidad' => 'BIOLOGIA QUIMICA', 'dni' => '26615690', 'condicion' => 'NOMBRADO', 'fechaNac' => '1964-07-15', 'celular' => '970091633', 'email' => 'marysa_1507@hotmail.com', 'sexo_id' => 2],
            ['num' => 29, 'nombre_completo' => 'Sánchez Bautista, Carmen Julio', 'cargo' => 'PROF. HORAS', 'especialidad' => 'LENGUA Y LITERATURA', 'dni' => '26663988', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-07-23', 'celular' => '950059806', 'email' => 'jusab7@hotmail.com', 'sexo_id' => 1],
            ['num' => 30, 'nombre_completo' => 'Ruiz Ruiz, Homero Héctor', 'cargo' => 'Auxiliar de Educación', 'especialidad' => 'EDUC.ARTISTICA', 'dni' => '26722878', 'condicion' => 'NOMBRADO', 'fechaNac' => '1963-07-05', 'celular' => '973710671', 'email' => 'homero563@hotmail.com', 'sexo_id' => 1],
            ['num' => 31, 'nombre_completo' => 'Castillo Chaupe, José Luis', 'cargo' => 'PROF. HORAS', 'especialidad' => 'CC.SS.', 'dni' => '43613379', 'condicion' => 'NOMBRADO', 'fechaNac' => '1986-06-08', 'celular' => '976892363', 'email' => 'navy-300@hotmail.com', 'sexo_id' => 1],
            ['num' => 32, 'nombre_completo' => 'Chávez Salazar, Jorge Enrique', 'cargo' => 'PROF. HORAS', 'especialidad' => 'EDUCACION FISICA', 'dni' => '26689762', 'condicion' => 'NOMBRADO', 'fechaNac' => '1969-06-14', 'celular' => '939412248', 'email' => 'jorge.chasa@hotmail.com', 'sexo_id' => 1],
            ['num' => 33, 'nombre_completo' => 'Cotrina Alva, Wilmar Antonio', 'cargo' => 'PROF. HORAS', 'especialidad' => 'INGLES', 'dni' => '17933733', 'condicion' => 'NOMBRADO', 'fechaNac' => '1965-06-13', 'celular' => '976950251', 'email' => 'wilmarcotrina21@gmail.com', 'sexo_id' => 1],
            ['num' => 34, 'nombre_completo' => 'Oblitas Vera, Luis Alberto', 'cargo' => 'PROF. HORAS', 'especialidad' => 'EPT', 'dni' => '40402885', 'condicion' => 'REASIGNADO', 'fechaNac' => '1978-06-24', 'celular' => '939911067', 'email' => 'technicaleduca@gamil.com', 'sexo_id' => 1],
            ['num' => 35, 'nombre_completo' => 'Terán Vargas, Elky Moisés', 'cargo' => 'Auxiliar de Educación', 'especialidad' => 'EDUCACION PRIMARIA', 'dni' => '28066972', 'condicion' => 'NOMBRADO', 'fechaNac' => '1972-06-28', 'celular' => '983612625', 'email' => 'emteva@hotmail.com', 'sexo_id' => 1],
            ['num' => 36, 'nombre_completo' => 'Chávez Castro, Flor Marleny', 'cargo' => 'PROF. HORAS', 'especialidad' => 'C Y T', 'dni' => '44212796', 'condicion' => 'CONTRATADO', 'fechaNac' => '1987-03-30', 'celular' => '973382185', 'email' => 'chavezcastromarlenne@gmail.com', 'sexo_id' => 2],
            ['num' => 37, 'nombre_completo' => 'Taico Zamora, Fabiola', 'cargo' => 'PROF. HORAS', 'especialidad' => 'MATEMÁTICA', 'dni' => '26601785', 'condicion' => 'NOMBRADO', 'fechaNac' => '1964-03-01', 'celular' => '943643044', 'email' => 'fabit_13@hotmail.com', 'sexo_id' => 2],
            ['num' => 38, 'nombre_completo' => 'Muñoz Castro, Luisa Elizabeth', 'cargo' => 'PROF. HORAS', 'especialidad' => 'COMUNICACIÓN', 'dni' => '41345043', 'condicion' => 'CONTRATADO', 'fechaNac' => '1982-05-24', 'celular' => '976140804', 'email' => 'luisa_8225@hotmail.com', 'sexo_id' => 2],
            ['num' => 39, 'nombre_completo' => 'Chugden Toledo, Edith Jhudy', 'cargo' => 'Auxiliar de Educación', 'especialidad' => 'LENGUAJE Y LITERATURA', 'dni' => '43049212', 'condicion' => 'CONTRATADO', 'fechaNac' => '1985-05-20', 'celular' => '944207860', 'email' => 'edith_0158@hotmail.com', 'sexo_id' => 2],
            ['num' => 40, 'nombre_completo' => 'Chávez Jauregui, Elva Cecilia', 'cargo' => 'PROF. HORAS', 'especialidad' => 'DPCC', 'dni' => '26685326', 'condicion' => 'NOMBRADO', 'fechaNac' => '1970-11-22', 'celular' => '973725969', 'email' => 'ceci_1314@outlook.com', 'sexo_id' => 2],
            ['num' => 41, 'nombre_completo' => 'Rios Rumay, Jhoicy Nataly', 'cargo' => 'PIP', 'especialidad' => 'COMPUT. E INFORMÁTICA', 'dni' => '71114930', 'condicion' => 'DESTACADO', 'fechaNac' => '1992-11-06', 'celular' => '957592783', 'email' => 'jriosrumay@gmail.com', 'sexo_id' => 2],
            ['num' => 42, 'nombre_completo' => 'Chilon Carrasco, Jeny Judith', 'cargo' => 'PROF. HORAS', 'especialidad' => 'COMPUTACION', 'dni' => '26731525', 'condicion' => 'NOMBRADO', 'fechaNac' => '1977-10-10', 'celular' => '970094633', 'email' => 'jenyjudith@hotmail.com', 'sexo_id' => 2],
            ['num' => 43, 'nombre_completo' => 'Llanos Abanto, César Enrique', 'cargo' => 'PROF. HORAS', 'especialidad' => 'EDUCACIÓN FÍSICA', 'dni' => '26688546', 'condicion' => 'NOMBRADO', 'fechaNac' => '1968-10-01', 'celular' => '979987398', 'email' => 'cesarito_2110@hotmail.com', 'sexo_id' => 1],
            ['num' => 44, 'nombre_completo' => 'CORREA FLORES, José Roberto', 'cargo' => 'VIGILANCIA', 'especialidad' => null, 'dni' => '26616765', 'condicion' => 'CONTRATADO', 'fechaNac' => '1970-09-02', 'celular' => null, 'email' => null, 'sexo_id' => 1],
            ['num' => 45, 'nombre_completo' => 'Alfaro Briones, Luz Maribel', 'cargo' => 'PROF. HORAS', 'especialidad' => 'CIENCIAS SOCIALES', 'dni' => '26688579', 'condicion' => 'NOMBRADO', 'fechaNac' => '1969-09-22', 'celular' => '999721654', 'email' => 'lumalbri_scm@hotmail.com', 'sexo_id' => 2],
            ['num' => 46, 'nombre_completo' => 'Astopilco Aliaga, Antonio Amadeo', 'cargo' => 'COORDINADOR DE TUTORIA', 'especialidad' => 'CIENCIAS NATURALES', 'dni' => '21637549', 'condicion' => 'PERSONAL JERÁRQUICO', 'fechaNac' => '1962-09-09', 'celular' => '976003913', 'email' => 'cl1707@hotmail.com', 'sexo_id' => 1],
            ['num' => 47, 'nombre_completo' => 'Trigoso Lopez, María Estela', 'cargo' => 'PROF. HORAS', 'especialidad' => 'LENGUAJE Y LITERATURA', 'dni' => '26623989', 'condicion' => 'NOMBRADO', 'fechaNac' => '1964-09-10', 'celular' => '981934933', 'email' => 'marieste_trilo@hotmail.com', 'sexo_id' => 2],
            ['num' => 48, 'nombre_completo' => 'VASQUEZ QUISPE, Lucy Anamelva', 'cargo' => 'TEC ADMI III', 'especialidad' => 'TE', 'dni' => '80576300', 'condicion' => 'NOMBRADO', 'fechaNac' => null, 'celular' => '976401280', 'email' => 'luvasqui@hotmail.com', 'sexo_id' => 2],
            ['num' => 49, 'nombre_completo' => 'CORONEL DÍAZ, Mesías', 'cargo' => 'VIGILANCIA', 'especialidad' => null, 'dni' => '27426278', 'condicion' => 'CONTRATADO', 'fechaNac' => null, 'celular' => null, 'email' => null, 'sexo_id' => 1],
            ['num' => 50, 'nombre_completo' => 'QUIROZ SALAS, Miguel Angel', 'cargo' => 'VIGILANCIA', 'especialidad' => null, 'dni' => '42442243', 'condicion' => 'CONTRATADO', 'fechaNac' => null, 'celular' => null, 'email' => null, 'sexo_id' => 1],
        ];

        $result = [];

        foreach ($raw as $r) {
            $parsed = $this->parseName($r['nombre_completo']);
            $cargoInfo = $this->normalizarCargo($r['cargo']);
            $condicion = $this->normalizarCondicion($r['condicion']);

            $result[] = [
                'num' => $r['num'],
                'paterno' => $parsed['paterno'],
                'materno' => $parsed['materno'],
                'nombre' => $parsed['nombre'],
                'dni' => $r['dni'],
                'sexo_id' => $r['sexo_id'],
                'fechaNac' => $r['fechaNac'],
                'celular' => $r['celular'],
                'email' => $r['email'] ? trim($r['email']) : null,
                'cargo_key' => $cargoInfo['cargo_key'],
                'rolTrabajador_id' => $cargoInfo['rolTrabajador_id'],
                'condicion' => $condicion,
                'especialidad' => $r['especialidad'] ? trim($r['especialidad']) : null,
            ];
        }

        return $result;
    }

    private function parseName(string $fullName): array
    {
        $fullName = trim(preg_replace('/\s+/', ' ', $fullName));

        if (str_contains($fullName, ',')) {
            [$apellidos, $nombres] = explode(',', $fullName, 2);
            $apellidos = trim($apellidos);
            $nombres = trim($nombres);

            $parts = explode(' ', $apellidos);

            if (count($parts) === 2) {
                return [
                    'paterno' => $parts[0],
                    'materno' => $parts[1],
                    'nombre' => $nombres,
                ];
            }

            if (count($parts) >= 3) {
                $connectors = ['DE', 'DEL', 'LA', 'LOS', 'LAS', 'Y'];
                $paterno = $parts[0];
                $materno = implode(' ', array_slice($parts, 1));

                if (in_array(mb_strtoupper($parts[1]), $connectors)) {
                    $paterno = implode(' ', array_slice($parts, 0, 3));
                    $materno = implode(' ', array_slice($parts, 3)) ?: '';
                } else {
                    $paterno = $parts[0];
                    $materno = implode(' ', array_slice($parts, 1));
                }

                return [
                    'paterno' => $paterno,
                    'materno' => $materno,
                    'nombre' => $nombres,
                ];
            }

            return [
                'paterno' => $apellidos,
                'materno' => '',
                'nombre' => $nombres,
            ];
        }

        $parts = explode(' ', $fullName);

        if (count($parts) >= 3) {
            return [
                'paterno' => $parts[0],
                'materno' => $parts[1],
                'nombre' => implode(' ', array_slice($parts, 2)),
            ];
        }

        if (count($parts) === 2) {
            return [
                'paterno' => $parts[0],
                'materno' => '',
                'nombre' => $parts[1],
            ];
        }

        return [
            'paterno' => $fullName,
            'materno' => '',
            'nombre' => '',
        ];
    }
}
