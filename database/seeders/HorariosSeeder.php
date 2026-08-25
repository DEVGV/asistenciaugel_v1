<?php

namespace Database\Seeders;

use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisCargaHoraria;
use App\Models\Conasis\ConasisDetalleHorarios;
use App\Models\Conasis\ConasisHorariosCursos;
use App\Models\Conasis\ConasisHorariosTrabajador;
use App\Models\CursosIE;
use App\Models\GradosIE;
use App\Models\Personas;
use App\Models\SeccionesIE;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorariosSeeder extends Seeder
{
    private int $ieId = 1;
    private int $anio = 2026;
    private int $toleranciaMin = 10;

    private array $diasMap = [
        'LUNES' => 1,
        'MARTES' => 2,
        'MIERCOLES' => 3,
        'MIÉRCOLES' => 3,
        'JUEVES' => 4,
        'VIERNES' => 5,
        'SABADO' => 6,
        'SÁBADO' => 6,
        'DOMINGO' => 7,
    ];

    private array $diasLetras = [
        1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D',
    ];

    /**
     * Mapeo: nombre en XLSX horario → paterno del sistema.
     * Se usa para match fuzzy de nombres.
     */
    /**
     * Mapeo explícito: nombre XLSX → [paterno, materno] del sistema.
     * null = docente no existe en el sistema.
     */
    private array $nombreMapeoManual = [
        'ABANTO URBINA, DAVID' => null,
        'AGULAR YDROGO,SANTIAGO' => ['AGUILAR', 'YDROGO'],
        'ALFARO BRIONES, MARIBEL' => ['ALFARO', 'BRIONES'],
        'ANTONIO ASTOPILCO ALIAGA' => ['ASTOPILCO', 'ALIAGA'],
        'ARANA DAVILA IRIS' => ['ARANA', 'DAVILA'],
        'ASCENCIO DEL CAMPO, SOCORRO' => ['ASCENCIO DEL CAMPO', ''],
        'BAUTISTA LLERENA SIXTO' => ['BAUTISTA', 'LLERENA'],
        'CAMACHO CERNA, AIDA' => null,
        'CASTILLO CHAUPE, JOSE' => ['CASTILLO', 'CHAUPE'],
        'CHAVEZ JAUREGUI, ELBA' => ['CHÁVEZ', 'JAUREGUI'],
        'CHAVEZ SALAZAR JORGE' => ['CHÁVEZ', 'SALAZAR'],
        'CHILON CARRASCO JHENY' => ['CHILON', 'CARRASCO'],
        'CONTRATO RELIGION' => null,
        'COTRINA ALVA, WILMAR' => ['COTRINA', 'ALVA'],
        'LINARES VASQUEZ, PEDRO' => ['LINARES', 'VASQUEZ'],
        'LLANOS ABANTO CESAR' => ['LLANOS', 'ABANTO'],
        'MARITZA RODRIGUEZ ATALAYA' => ['RODRIGUEZ', 'ATALAYA'],
        'MORALES GOICOCHEA ALBERTO' => ['MORALES', 'GOICOCHEA'],
        'MOYA ZAVALETA MARCIAL' => ['MOYA', 'ZAVALETA'],
        'NORIEGA PIZARRO, BENJAMIN' => ['NORIEGA', 'PIZARRO'],
        'PLAZA CAHUANA SANCHEZ' => null,
        'PLAZA CARMEN MEJIA GUEVARA' => null,
        'PLAZA CASTREJON VILLACORTA' => null,
        'PLAZA MANUEL ALCALDE' => null,
        'PLAZA NESTOR MARCHENA' => null,
        'PLAZA TELLO SANTILLAN' => null,
        'PLAZA VASQUEZ MURRUGARRA, SEGUNDO' => null,
        'PLAZA VELASQUEZ VELASQUEZ, VIDAL' => null,
        'SAENZ CASANOVA NELLY' => ['SÁENZ', 'CASANOVA'],
        'SALAZAR SALAZAR WILFREDO' => ['SALAZAR', 'SALAZAR'],
        'SALDAÑA ALVARADO MIRTA' => ['SALDAÑA', 'ALVARADO DE TORREL'],
        'SANCHEZ BAUTISTA JULIO' => ['SÁNCHEZ', 'BAUTISTA'],
        'TAICO ZAMORA FABIOLA' => ['TAICO', 'ZAMORA'],
        'TELLO ACOSTA, ISABEL' => ['TELLO', 'ACOSTA'],
        'TIMANA PALACIOS, JESSICA' => null,
        'TRIGOSO LOPEZ ESTELA' => ['TRIGOSO', 'LOPEZ'],
        'URTEAGA VILLAR, ALBERTO' => null,
    ];

    public function run(): void
    {
        $this->command->info('=== Registrando grados, secciones, cursos y horarios ===');

        $rows = $this->getXlsxData();
        $this->command->info("Filas del XLSX: " . count($rows));

        DB::transaction(function () use ($rows) {
            // 1. Crear grados y secciones
            $this->command->info('1. Creando grados y secciones...');
            $seccionesMap = $this->crearGradosYSecciones($rows);
            $this->command->info('   Secciones creadas: ' . count($seccionesMap));

            // 2. Crear cursos
            $this->command->info('2. Creando cursos...');
            $cursosMap = $this->crearCursos($rows);
            $this->command->info('   Cursos creados: ' . count($cursosMap));

            // 3. Mapear docentes del XLSX a trabajadores del sistema
            $this->command->info('3. Mapeando docentes...');
            $docentesMap = $this->mapearDocentes($rows);

            // 4. Crear horarios de cursos
            $this->command->info('4. Creando horarios de cursos...');
            $horariosCursosMap = $this->crearHorariosCursos($rows, $seccionesMap, $cursosMap);
            $this->command->info('   Slots de horario-curso creados: ' . count($horariosCursosMap));

            // 5. Crear cargas horarias y horarios de trabajador
            $this->command->info('5. Creando cargas horarias y horarios de trabajador...');
            $this->crearCargasYHorariosTrabajador($rows, $docentesMap, $horariosCursosMap, $seccionesMap, $cursosMap);
        });
    }

    private function crearGradosYSecciones(array $rows): array
    {
        $gradosSeccionesRaw = [];
        foreach ($rows as $r) {
            $gs = mb_strtoupper(trim($r['grado_seccion']));
            if ($gs && !isset($gradosSeccionesRaw[$gs])) {
                $gradosSeccionesRaw[$gs] = $this->parseGradoSeccion($gs);
            }
        }

        $seccionesMap = [];
        $gradosCreados = [];

        foreach ($gradosSeccionesRaw as $raw => $parsed) {
            $gradoNombre = $parsed['grado'];
            $seccionNombre = $parsed['seccion'];

            if (!isset($gradosCreados[$gradoNombre])) {
                $grado = GradosIE::where('institucionEduc_id', $this->ieId)
                    ->where('nombre', $gradoNombre)
                    ->first();

                if (!$grado) {
                    $grado = GradosIE::create([
                        'institucionEduc_id' => $this->ieId,
                        'nombre' => $gradoNombre,
                        'sigla' => $parsed['grado_sigla'],
                        'created_by' => 1,
                        'activo' => true,
                    ]);
                }
                $gradosCreados[$gradoNombre] = $grado->id;
            }

            $gradoId = $gradosCreados[$gradoNombre];

            $seccion = SeccionesIE::where('grado_id', $gradoId)
                ->where('nombre', $seccionNombre)
                ->first();

            if (!$seccion) {
                $seccion = SeccionesIE::create([
                    'grado_id' => $gradoId,
                    'nombre' => $seccionNombre,
                    'sigla' => $seccionNombre,
                    'created_by' => 1,
                    'activo' => true,
                ]);
            }

            $seccionesMap[$raw] = $seccion->id;
        }

        return $seccionesMap;
    }

    private function parseGradoSeccion(string $raw): array
    {
        // Formato: "2N", "3K", "5M", etc.
        // Número = grado, letra = sección
        preg_match('/^(\d+)([A-Z]+)$/', $raw, $matches);

        if (count($matches) < 3) {
            return [
                'grado' => $raw,
                'grado_sigla' => $raw,
                'seccion' => $raw,
            ];
        }

        $num = $matches[1];
        $letra = $matches[2];

        $gradoNombres = [
            '1' => '1° GRADO',
            '2' => '2° GRADO',
            '3' => '3° GRADO',
            '4' => '4° GRADO',
            '5' => '5° GRADO',
        ];

        return [
            'grado' => $gradoNombres[$num] ?? $num . '° GRADO',
            'grado_sigla' => $num . '°',
            'seccion' => $letra,
        ];
    }

    private function crearCursos(array $rows): array
    {
        $cursosUnicos = [];
        foreach ($rows as $r) {
            $curso = trim($r['curso']);
            $cursoKey = mb_strtoupper($curso);
            if ($cursoKey && !isset($cursosUnicos[$cursoKey])) {
                $cursosUnicos[$cursoKey] = $curso;
            }
        }

        $cursosMap = [];
        foreach ($cursosUnicos as $key => $nombre) {
            $curso = CursosIE::where('institucionEduc_id', $this->ieId)
                ->whereRaw('UPPER(nombre) = ?', [$key])
                ->first();

            if (!$curso) {
                $sigla = $this->generarSiglaCurso($nombre);
                $curso = CursosIE::create([
                    'institucionEduc_id' => $this->ieId,
                    'nombre' => $nombre,
                    'sigla' => $sigla,
                    'created_by' => 1,
                    'activo' => true,
                ]);
            }

            $cursosMap[$key] = $curso->id;
        }

        return $cursosMap;
    }

    private function generarSiglaCurso(string $nombre): string
    {
        $siglas = [
            'Matemática' => 'MAT',
            'Comunicación' => 'COM',
            'Ciencia y Tecnología' => 'CYT',
            'Ciencias Sociales' => 'CCSS',
            'DPCC' => 'DPCC',
            'Inglés' => 'ING',
            'Arte y Cultura' => 'AYC',
            'Educación Física' => 'EF',
            'Educación Religiosa' => 'ER',
            'Educación para el Trabajo' => 'EPT',
            'Tutoría' => 'TUT',
        ];

        return $siglas[$nombre] ?? mb_strtoupper(mb_substr($nombre, 0, 3));
    }

    private function mapearDocentes(array $rows): array
    {
        $docentesXlsx = [];
        foreach ($rows as $r) {
            $nombre = trim($r['docente']);
            if ($nombre && !isset($docentesXlsx[$nombre])) {
                $docentesXlsx[$nombre] = true;
            }
        }

        $personas = Personas::query()
            ->join('t_trabajador', 't_trabajador.persona_id', '=', 't_personas.id')
            ->join('t_altasTrabajadores', function ($join) {
                $join->on('t_altasTrabajadores.trabajador_id', '=', 't_trabajador.id')
                    ->where('t_altasTrabajadores.institucionEducativa_id', $this->ieId)
                    ->whereNull('t_altasTrabajadores.fechaBaja');
            })
            ->select(
                't_personas.id as persona_id',
                't_personas.paterno',
                't_personas.materno',
                't_personas.nombre',
                't_trabajador.id as trabajador_id',
                't_altasTrabajadores.id as alta_id'
            )
            ->get();

        $docentesMap = [];
        $sinMatch = [];
        $conMatch = [];

        foreach (array_keys($docentesXlsx) as $nombreXlsx) {
            $nombreUpper = mb_strtoupper($nombreXlsx);
            $matched = false;

            // Intento 1: mapeo manual con paterno+materno exactos
            if (array_key_exists($nombreXlsx, $this->nombreMapeoManual)) {
                $mapeo = $this->nombreMapeoManual[$nombreXlsx];
                if ($mapeo === null) {
                    $sinMatch[] = $nombreXlsx;
                    continue;
                }

                [$paternoExpected, $maternoExpected] = $mapeo;

                $persona = $personas->first(function ($p) use ($paternoExpected, $maternoExpected) {
                    $patMatch = $this->sinTildes(mb_strtoupper($p->paterno)) === $this->sinTildes(mb_strtoupper($paternoExpected));
                    if (!$patMatch) return false;

                    if (!empty($maternoExpected)) {
                        $matMatch = $this->sinTildes(mb_strtoupper($p->materno)) === $this->sinTildes(mb_strtoupper($maternoExpected));
                        return $matMatch;
                    }
                    return true;
                });

                if ($persona) {
                    $docentesMap[$nombreXlsx] = [
                        'trabajador_id' => $persona->trabajador_id,
                        'alta_id' => $persona->alta_id,
                    ];
                    $conMatch[] = $nombreXlsx . ' → ' . $persona->paterno . ' ' . $persona->materno . ', ' . $persona->nombre;
                    $matched = true;
                }
            }

            if (!$matched && !in_array($nombreXlsx, $sinMatch)) {
                // Intento 2: búsqueda fuzzy por apellido
                $palabras = preg_split('/[\s,]+/', $this->sinTildes($nombreUpper));
                $palabras = array_filter($palabras, fn($p) => strlen($p) >= 3);

                $persona = $personas->first(function ($p) use ($palabras) {
                    $completo = $this->sinTildes(mb_strtoupper($p->paterno . ' ' . $p->materno . ' ' . $p->nombre));
                    $matchCount = 0;
                    foreach ($palabras as $palabra) {
                        if (str_contains($completo, $palabra)) {
                            $matchCount++;
                        }
                    }
                    return $matchCount >= 2;
                });

                if ($persona) {
                    $docentesMap[$nombreXlsx] = [
                        'trabajador_id' => $persona->trabajador_id,
                        'alta_id' => $persona->alta_id,
                    ];
                    $conMatch[] = $nombreXlsx . ' → ' . $persona->paterno . ' ' . $persona->materno . ', ' . $persona->nombre;
                } else {
                    $sinMatch[] = $nombreXlsx;
                }
            }
        }

        $this->command->info('   Docentes mapeados: ' . count($conMatch));
        foreach ($conMatch as $m) {
            $this->command->line("     ✓ {$m}");
        }

        if (count($sinMatch) > 0) {
            $this->command->warn('   Docentes SIN MATCH en el sistema (' . count($sinMatch) . '):');
            foreach ($sinMatch as $s) {
                $this->command->warn("     ✗ {$s}");
            }
        }

        return $docentesMap;
    }

    private function crearHorariosCursos(array $rows, array $seccionesMap, array $cursosMap): array
    {
        $map = [];

        foreach ($rows as $r) {
            $gsKey = mb_strtoupper(trim($r['grado_seccion']));
            $cursoKey = mb_strtoupper(trim($r['curso']));
            $diaKey = mb_strtoupper(trim($r['dia']));
            $nroDia = $this->diasMap[$diaKey] ?? 0;
            $periodo = (int) $r['periodo'];

            if (!isset($seccionesMap[$gsKey]) || !isset($cursosMap[$cursoKey]) || !$nroDia) {
                continue;
            }

            [$horaInicio, $horaFin] = $this->parseHora($r['hora']);
            $minAcum = $this->calcMinAcum($horaInicio, $horaFin);

            $uniqueKey = "{$gsKey}_{$cursoKey}_{$nroDia}_{$periodo}";

            if (isset($map[$uniqueKey])) {
                continue;
            }

            $hc = ConasisHorariosCursos::create([
                'anio' => $this->anio,
                'seccion_id' => $seccionesMap[$gsKey],
                'curso_id' => $cursosMap[$cursoKey],
                'diaSemana' => 'S',
                'nroDia' => $nroDia,
                'horaInicio' => $horaInicio,
                'horaFin' => $horaFin,
                'minAcum' => $minAcum,
                'created_by' => 1,
            ]);

            $map[$uniqueKey] = $hc->id;
        }

        return $map;
    }

    private function crearCargasYHorariosTrabajador(
        array $rows,
        array $docentesMap,
        array $horariosCursosMap,
        array $seccionesMap,
        array $cursosMap
    ): void {
        // Agrupar filas por docente
        $porDocente = [];
        foreach ($rows as $r) {
            $docente = trim($r['docente']);
            $porDocente[$docente][] = $r;
        }

        $totalCargas = 0;
        $totalHorarios = 0;

        foreach ($porDocente as $nombreDocente => $filasDocente) {
            if (!isset($docentesMap[$nombreDocente])) {
                continue;
            }

            $trabajadorId = $docentesMap[$nombreDocente]['trabajador_id'];
            $altaId = $docentesMap[$nombreDocente]['alta_id'];

            // Crear cargas horarias para cada slot
            $cargasCreadas = [];
            foreach ($filasDocente as $r) {
                $gsKey = mb_strtoupper(trim($r['grado_seccion']));
                $cursoKey = mb_strtoupper(trim($r['curso']));
                $diaKey = mb_strtoupper(trim($r['dia']));
                $nroDia = $this->diasMap[$diaKey] ?? 0;
                $periodo = (int) $r['periodo'];

                $uniqueKey = "{$gsKey}_{$cursoKey}_{$nroDia}_{$periodo}";

                if (!isset($horariosCursosMap[$uniqueKey])) {
                    continue;
                }

                $hcId = $horariosCursosMap[$uniqueKey];

                $existeCarga = ConasisCargaHoraria::where('horarioCurso_id', $hcId)
                    ->where('trabajador_id', $trabajadorId)
                    ->exists();

                if (!$existeCarga) {
                    ConasisCargaHoraria::create([
                        'horarioCurso_id' => $hcId,
                        'trabajador_id' => $trabajadorId,
                        'altaTrabajador_id' => $altaId,
                        'fechaInicio' => now()->toDateString(),
                        'titularSuplencia' => 'T',
                        'created_by' => 1,
                    ]);
                    $totalCargas++;
                }

                $cargasCreadas[$nroDia][] = $r;
            }

            // Crear horario trabajador consolidado
            $alta = AltasTrabajadores::find($altaId);

            $horarioTrab = ConasisHorariosTrabajador::updateOrCreate(
                [
                    'anio' => $this->anio,
                    'institucionEduc_id' => $this->ieId,
                    'trabajador_id' => $trabajadorId,
                ],
                [
                    'altaTrabajador_id' => $altaId,
                    'nombre' => 'Horario del Docente ' . $this->anio,
                    'tipoHorario' => '1',
                    'fechaInicio' => $alta?->fechaInicio ?? Carbon::create($this->anio, 1, 1)->toDateString(),
                    'fechaFin' => $alta?->fechaFin,
                    'archivado' => false,
                    'activo' => true,
                    'created_by' => 1,
                ]
            );

            // Crear detalles por día con tolerancia de 10 min
            ConasisDetalleHorarios::where('horarioTrabajador_id', $horarioTrab->id)->delete();

            foreach ($cargasCreadas as $nroDia => $filasDia) {
                $horasIni = [];
                $horasFin = [];
                $hcIniId = null;
                $hcFinId = null;
                $minEntrada = '23:59';
                $maxSalida = '00:00';

                foreach ($filasDia as $r) {
                    [$hi, $hf] = $this->parseHora($r['hora']);
                    $horasIni[] = $hi;
                    $horasFin[] = $hf;

                    if ($hi < $minEntrada) {
                        $minEntrada = $hi;
                        $gsKey = mb_strtoupper(trim($r['grado_seccion']));
                        $cursoKey = mb_strtoupper(trim($r['curso']));
                        $periodo = (int) $r['periodo'];
                        $hcIniId = $horariosCursosMap["{$gsKey}_{$cursoKey}_{$nroDia}_{$periodo}"] ?? null;
                    }
                    if ($hf > $maxSalida) {
                        $maxSalida = $hf;
                        $gsKey = mb_strtoupper(trim($r['grado_seccion']));
                        $cursoKey = mb_strtoupper(trim($r['curso']));
                        $periodo = (int) $r['periodo'];
                        $hcFinId = $horariosCursosMap["{$gsKey}_{$cursoKey}_{$nroDia}_{$periodo}"] ?? null;
                    }
                }

                // Calcular horas acumuladas
                $totalMin = 0;
                for ($i = 0; $i < count($horasIni); $i++) {
                    $totalMin += $this->calcMinAcum($horasIni[$i], $horasFin[$i]);
                }

                // Tolerancia de 10 min: la entrada se abre 10 min antes
                $entHoraInicio = $this->restarMinutos($minEntrada, $this->toleranciaMin);
                $entHoraFin = $minEntrada;

                // Tolerancia de 10 min: la salida se cierra 10 min después
                $salHoraInicio = $maxSalida;
                $salHoraFin = $this->sumarMinutos($maxSalida, $this->toleranciaMin);

                // Determinar turno
                $horaEnt = (int) explode(':', $minEntrada)[0];
                if ($horaEnt < 13) {
                    $turnoId = 1;
                    $nombreTurno = 'MAÑANA';
                } elseif ($horaEnt < 18) {
                    $turnoId = 2;
                    $nombreTurno = 'TARDE';
                } else {
                    $turnoId = 3;
                    $nombreTurno = 'NOCHE';
                }

                ConasisDetalleHorarios::create([
                    'horarioTrabajador_id' => $horarioTrab->id,
                    'turno_id' => $turnoId,
                    'nombreTurno' => $nombreTurno,
                    'nroTurno' => $turnoId,
                    'diaSemana' => 'S',
                    'nroDia' => $nroDia,
                    'horarioCursoIni_id' => $hcIniId,
                    'entDiaInicio' => 0,
                    'entDiaFin' => 0,
                    'entHoraInicio' => $entHoraInicio,
                    'entHoraFin' => $entHoraFin,
                    'entTolerancia' => $this->toleranciaMin,
                    'horarioCursoFin_id' => $hcFinId,
                    'salDiaInicio' => 0,
                    'salDiaFin' => 0,
                    'salHoraInicio' => $salHoraInicio,
                    'salHoraFin' => $salHoraFin,
                    'salTolerancia' => $this->toleranciaMin,
                    'horaAcumula' => $totalMin / 60.0,
                    'aplicar' => true,
                    'created_by' => 1,
                ]);
            }

            $totalHorarios++;
        }

        $this->command->info("   Cargas horarias creadas: {$totalCargas}");
        $this->command->info("   Horarios trabajador creados: {$totalHorarios}");
    }

    private function parseHora(string $hora): array
    {
        $parts = explode('-', trim($hora));
        $inicio = trim($parts[0]);
        $fin = trim($parts[1] ?? $inicio);

        // Normalizar formato HH:MM
        if (substr_count($inicio, ':') === 1) {
            [$h, $m] = explode(':', $inicio);
            $inicio = sprintf('%02d:%02d', (int)$h, (int)$m);
        }
        if (substr_count($fin, ':') === 1) {
            [$h, $m] = explode(':', $fin);
            $fin = sprintf('%02d:%02d', (int)$h, (int)$m);
        }

        return [$inicio, $fin];
    }

    private function calcMinAcum(string $horaInicio, string $horaFin): float
    {
        [$h1, $m1] = array_map('intval', explode(':', $horaInicio));
        [$h2, $m2] = array_map('intval', explode(':', $horaFin));
        return (float)(($h2 * 60 + $m2) - ($h1 * 60 + $m1));
    }

    private function restarMinutos(string $hora, int $minutos): string
    {
        [$h, $m] = array_map('intval', explode(':', $hora));
        $totalMin = $h * 60 + $m - $minutos;
        if ($totalMin < 0) $totalMin = 0;
        return sprintf('%02d:%02d', intdiv($totalMin, 60), $totalMin % 60);
    }

    private function sumarMinutos(string $hora, int $minutos): string
    {
        [$h, $m] = array_map('intval', explode(':', $hora));
        $totalMin = $h * 60 + $m + $minutos;
        return sprintf('%02d:%02d', intdiv($totalMin, 60), $totalMin % 60);
    }

    private function sinTildes(string $str): string
    {
        $search = ['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ','Ü','ü'];
        $replace = ['A','E','I','O','U','a','e','i','o','u','N','n','U','u'];
        return str_replace($search, $replace, $str);
    }

    private function getXlsxData(): array
    {
        $file = '/tmp/Horario_Docentes_San_Ramon_2026.xlsx';

        // Usar PhpSpreadsheet si está disponible, o leer desde un export previo
        // Como el proyecto es Laravel, usamos la librería disponible
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getSheetByName('Detalle');

        $rows = [];
        $maxRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $maxRow; $row++) {
            $docente = trim((string) $sheet->getCell("A{$row}")->getValue());
            if (empty($docente)) continue;

            $rows[] = [
                'docente' => $docente,
                'dia' => trim((string) $sheet->getCell("B{$row}")->getValue()),
                'periodo' => (int) $sheet->getCell("C{$row}")->getValue(),
                'hora' => trim((string) $sheet->getCell("D{$row}")->getValue()),
                'grado_seccion' => trim((string) $sheet->getCell("E{$row}")->getValue()),
                'curso' => trim((string) $sheet->getCell("F{$row}")->getValue()),
            ];
        }

        return $rows;
    }
}
