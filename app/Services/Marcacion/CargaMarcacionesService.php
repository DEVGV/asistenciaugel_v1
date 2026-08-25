<?php

namespace App\Services\Marcacion;

use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisLocalesMarcacion;
use App\Models\Conasis\ConasisMarcaciones;
use App\Models\Conasis\ConasisRelojes;
use App\Models\Personas;
use App\Models\Trabajador;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CargaMarcacionesService
{
    /**
     * Procesa un archivo Excel de marcaciones para un reloj dado.
     *
     * @return array{
     *     insertados: int,
     *     duplicados: int,
     *     errores: array<int, string>,
     *     preview: array<int, array{dni: string, nombre: string, fecha: string, tipo: string}>,
     *     resumen: array{totalFilas: int, trabajadoresAsociados: int, sinCoincidencia: int, fechaMin: string|null, fechaMax: string|null}
     * }
     */
    public function procesar(string $filePath, int $relojId, bool $overwrite = false): array
    {
        $reloj = ConasisRelojes::with('localInstEduc')->findOrFail($relojId);
        $localInstEduc = $reloj->localInstEduc;
        $ieId = $localInstEduc->institucionEduc_id;

        // ── Leer Excel ──────────────────────────────────────────────────────
        $filas = $this->leerExcel($filePath);

        if (empty($filas)) {
            return [
                'insertados' => 0,
                'duplicados' => 0,
                'errores'    => [1 => 'El archivo no contiene filas de datos.'],
                'preview'    => [],
                'resumen'    => ['totalFilas' => 0, 'trabajadoresAsociados' => 0, 'sinCoincidencia' => 0, 'fechaMin' => null, 'fechaMax' => null],
            ];
        }

        // ── Pre-cargar DNIs → trabajador_id ─────────────────────────────────
        $dnis = array_unique(array_column($filas, 'dni'));

        $dniToTrabajador = Personas::whereIn('docIdentidad', $dnis)
            ->whereHas('trabajador')
            ->with('trabajador:id,persona_id')
            ->get(['id', 'docIdentidad', 'paterno', 'materno', 'nombre'])
            ->keyBy('docIdentidad');

        // Pre-cargar altas activas de esos trabajadores en esta IE
        $trabajadorIds = $dniToTrabajador->map(fn ($p) => $p->trabajador->id)->values()->all();

        $altasActivas = AltasTrabajadores::where('institucionEducativa_id', $ieId)
            ->whereIn('trabajador_id', $trabajadorIds)
            ->whereNull('fechaBaja')
            ->get()
            ->keyBy('trabajador_id');

        // NOTA: NO se resuelve un localMarcacion_id "global" del reloj: cada
        // t_localesMarcacion pertenece a UN trabajador, así que hay que
        // resolverlo por trabajador dentro del loop de filas (ver más abajo).
        // Precargamos los localesMarcacion vigentes de los trabajadores en este local.
        $localesMarcacionPorTrab = ConasisLocalesMarcacion::where('localInstEduc_id', $localInstEduc->id)
            ->whereIn('trabajador_id', $trabajadorIds)
            ->where(function ($q) {
                $q->whereNull('fechaFin')
                    ->orWhere('fechaFin', '>=', now()->toDateString());
            })
            ->get()
            ->keyBy('trabajador_id');

        // ── Procesar filas ──────────────────────────────────────────────────
        $createdBy = auth()->id() ?? 1;
        $ahora     = now();
        $errores   = [];
        $validas   = [];
        $preview   = [];
        $fechas    = [];

        foreach ($filas as $idx => $fila) {
            $rowNum = $idx + 2; // +1 por 0-index, +1 por header
            $dni    = trim((string) $fila['dni']);
            $fechaRaw = trim((string) $fila['fecha']);

            // Validar campos obligatorios
            if ($dni === '') {
                $errores[$rowNum] = 'DNI vacío';
                continue;
            }
            if ($fechaRaw === '') {
                $errores[$rowNum] = 'Fecha y Hora vacío';
                continue;
            }

            // Parsear fecha
            $fechaMarcacion = $this->parsearFecha($fechaRaw);
            if ($fechaMarcacion === null) {
                $errores[$rowNum] = "Formato de fecha inválido: '{$fechaRaw}'. Use AAAA-MM-DD HH:MM:SS";
                continue;
            }

            // Resolver trabajador
            $persona = $dniToTrabajador->get($dni);
            if (! $persona) {
                $errores[$rowNum] = "DNI {$dni} no encontrado en el sistema";
                continue;
            }

            $trabajadorId = $persona->trabajador->id;

            // Resolver alta activa en esta IE
            $alta = $altasActivas->get($trabajadorId);
            if (! $alta) {
                $errores[$rowNum] = "DNI {$dni} ({$persona->nombre_completo}) no tiene alta activa en esta IE";
                continue;
            }

            // Resolver localMarcacion_id específico del trabajador; si no existe,
            // se crea sobre la marcha para no romper el JOIN del SP.
            $lm = $localesMarcacionPorTrab->get($trabajadorId);
            if (! $lm) {
                $lm = ConasisLocalesMarcacion::create([
                    'trabajador_id'     => $trabajadorId,
                    'altaTrabajador_id' => $alta->id,
                    'localInstEduc_id'  => $localInstEduc->id,
                    'fechaInicio'       => $fechaMarcacion->toDateString(),
                    'created_by'        => $createdBy,
                    'created_at'        => $ahora,
                ]);
                $localesMarcacionPorTrab->put($trabajadorId, $lm);
            }
            $localMarcacionId = $lm->id;

            $fechas[] = $fechaMarcacion->toDateString();

            $validas[] = [
                'trabajador_id'      => $trabajadorId,
                'altaTrabajador_id'  => $alta->id,
                'localMarcacion_id'  => $localMarcacionId,
                'reloj_id'           => $relojId,
                'fechaMarcacion'     => $fechaMarcacion->format('Y-m-d H:i:s'),
                'fechaRegistro'      => $ahora->format('Y-m-d H:i:s'),
                'tipo'               => 'A',
                'medioMarcacion'     => 'R',
                'procesado'          => false,
                'created_by'         => $createdBy,
            ];

            // Preview (máx 10 filas)
            if (count($preview) < 10) {
                $preview[] = [
                    'dni'    => $dni,
                    'nombre' => $persona->nombre_completo,
                    'fecha'  => $fechaMarcacion->format('Y-m-d H:i:s'),
                    'tipo'   => 'Asistencia',
                ];
            }
        }

        if (empty($validas)) {
            return [
                'insertados' => 0,
                'duplicados' => 0,
                'errores'    => $errores,
                'preview'    => $preview,
                'resumen'    => [
                    'totalFilas'            => count($filas),
                    'trabajadoresAsociados' => 0,
                    'sinCoincidencia'       => count($errores),
                    'fechaMin'              => null,
                    'fechaMax'              => null,
                ],
            ];
        }

        // ── Insertar en BD ──────────────────────────────────────────────────
        $insertados = 0;
        $duplicados = 0;

        DB::transaction(function () use ($validas, $overwrite, &$insertados, &$duplicados) {
            foreach ($validas as $data) {
                // Verificar duplicado: misma marcación (trabajador + fecha exacta)
                $existente = ConasisMarcaciones::where('trabajador_id', $data['trabajador_id'])
                    ->where('fechaMarcacion', $data['fechaMarcacion'])
                    ->first();

                if ($existente) {
                    if ($overwrite) {
                        $existente->update([
                            'altaTrabajador_id'  => $data['altaTrabajador_id'],
                            'localMarcacion_id'  => $data['localMarcacion_id'],
                            'reloj_id'           => $data['reloj_id'],
                            'tipo'               => $data['tipo'],
                            'medioMarcacion'     => $data['medioMarcacion'],
                            'procesado'          => $data['procesado'],
                            'fechaRegistro'      => $data['fechaRegistro'],
                        ]);
                        $insertados++;
                    } else {
                        $duplicados++;
                    }
                    continue;
                }

                ConasisMarcaciones::create($data);
                $insertados++;
            }
        });

        // Trabajadores únicos asociados
        $trabajadoresAsociados = count(array_unique(array_column($validas, 'trabajador_id')));
        $sinCoincidencia = count($errores);

        return [
            'insertados' => $insertados,
            'duplicados' => $duplicados,
            'errores'    => $errores,
            'preview'    => $preview,
            'resumen'    => [
                'totalFilas'            => count($filas),
                'trabajadoresAsociados' => $trabajadoresAsociados,
                'sinCoincidencia'       => $sinCoincidencia,
                'fechaMin'              => ! empty($fechas) ? min($fechas) : null,
                'fechaMax'              => ! empty($fechas) ? max($fechas) : null,
            ],
        ];
    }

    /**
     * Lee el Excel y devuelve un array de filas con claves 'dni' y 'fecha'.
     *
     * @return array<int, array{dni: string, fecha: string}>
     */
    private function leerExcel(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $filas = [];

        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $dni   = $sheet->getCell("A{$row}")->getValue();
            $fecha = $sheet->getCell("B{$row}")->getValue();

            // Saltar filas completamente vacías
            if (($dni === null || $dni === '') && ($fecha === null || $fecha === '')) {
                continue;
            }

            // Si la fecha es un número (formato serial de Excel), convertir
            if (is_numeric($fecha)) {
                try {
                    $dateTime = ExcelDate::excelToDateTimeObject((float) $fecha);
                    $fecha = $dateTime->format('Y-m-d H:i:s');
                } catch (\Throwable) {
                    // Dejar como está, se marcará como error de parseo
                }
            }

            $filas[] = [
                'dni'   => (string) $dni,
                'fecha' => (string) $fecha,
            ];
        }

        return $filas;
    }

    /**
     * Intenta parsear una fecha en varios formatos comunes.
     */
    private function parsearFecha(string $raw): ?\Carbon\Carbon
    {
        $formatos = [
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            'Y/m/d H:i:s',
            'Y/m/d H:i',
        ];

        foreach ($formatos as $fmt) {
            try {
                return \Carbon\Carbon::createFromFormat($fmt, $raw);
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }
}
