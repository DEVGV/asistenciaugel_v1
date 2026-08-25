<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de marcaciones de asistencia para el docente Keyla Lisbeth Rojas (trabajador_id = 9).
 *
 * Genera marcas realistas (entrada/salida) del 01-06-2026 al 24-07-2026
 * respetando el horario asignado:
 *   - Lunes     (nroDia=1): entrada 08:00, salida 12:30
 *   - Martes    (nroDia=2): entrada 09:00, salida 11:30
 *   - Miércoles (nroDia=3): entrada 08:00, salida 11:30
 *   - Viernes   (nroDia=5): entrada 11:00, salida 12:30
 *
 * Tipos de marca (campo `tipo`):
 *   E = Entrada, S = Salida
 *
 * Escenarios aleatorios con distribución realista:
 *   60% → puntual (dentro de tolerancia ±5 min)
 *   15% → tardanza (5-25 min después)
 *   10% → salida temprana
 *   10% → no asiste (sin marca)
 *    5% → solo entra (sin salida)
 */
class MarcacionesDocente2026Seeder extends Seeder
{
    private const TRABAJADOR_ID = 9;

    private const ALTA_ID = 13;

    /** @var array<int, array{entrada: string, salida: string}> nroDia → horario */
    private const HORARIO = [
        1 => ['entrada' => '08:00', 'salida' => '12:30'], // Lunes
        2 => ['entrada' => '09:00', 'salida' => '11:30'], // Martes
        3 => ['entrada' => '08:00', 'salida' => '11:30'], // Miércoles
        5 => ['entrada' => '11:00', 'salida' => '12:30'], // Viernes
    ];

    public function run(): void
    {
        $fechaInicio = Carbon::parse('2026-06-01');
        $fechaFin = Carbon::parse('2026-07-24');

        $marcaciones = [];

        $current = $fechaInicio->copy();

        while ($current->lte($fechaFin)) {
            $nroDia = $current->dayOfWeekIso; // 1=Lun ... 7=Dom

            if (isset(self::HORARIO[$nroDia])) {
                $horario = self::HORARIO[$nroDia];
                $escenario = $this->sortearEscenario();

                match ($escenario) {
                    'puntual' => $this->agregarMarcasPuntuales($marcaciones, $current, $horario),
                    'tardanza' => $this->agregarMarcasTardanza($marcaciones, $current, $horario),
                    'salida_temprana' => $this->agregarMarcasSalidaTemprana($marcaciones, $current, $horario),
                    'solo_entrada' => $this->agregarSoloEntrada($marcaciones, $current, $horario),
                    'no_asiste' => null, // Sin marcas
                };
            }

            $current->addDay();
        }

        // Insertar todas las marcaciones en bloque
        if (! empty($marcaciones)) {
            DB::table('conasis.t_marcaciones')->insert($marcaciones);
        }

        $this->command->info(sprintf(
            'Se insertaron %d marcaciones para el docente trabajador_id=%d.',
            count($marcaciones),
            self::TRABAJADOR_ID
        ));
    }

    /**
     * Sortea el escenario de asistencia con pesos realistas.
     */
    private function sortearEscenario(): string
    {
        $rand = rand(1, 100);

        return match (true) {
            $rand <= 60 => 'puntual',
            $rand <= 75 => 'tardanza',
            $rand <= 85 => 'salida_temprana',
            $rand <= 95 => 'solo_entrada',
            default => 'no_asiste',
        };
    }

    /**
     * Entrada puntual (±5 min) y salida puntual (±5 min).
     *
     * @param  array<int, array<string, mixed>>  $marcaciones
     * @param  array{entrada: string, salida: string}  $horario
     */
    private function agregarMarcasPuntuales(array &$marcaciones, Carbon $fecha, array $horario): void
    {
        $deltaEntrada = rand(-3, 5);
        $deltaSalida = rand(-4, 5);

        $marcaciones[] = $this->buildMarca($fecha, $horario['entrada'], $deltaEntrada, 'E');
        $marcaciones[] = $this->buildMarca($fecha, $horario['salida'], $deltaSalida, 'S');
    }

    /**
     * Entrada con tardanza (5-25 min después) y salida puntual.
     *
     * @param  array<int, array<string, mixed>>  $marcaciones
     * @param  array{entrada: string, salida: string}  $horario
     */
    private function agregarMarcasTardanza(array &$marcaciones, Carbon $fecha, array $horario): void
    {
        $deltaEntrada = rand(6, 25);
        $deltaSalida = rand(-4, 5);

        $marcaciones[] = $this->buildMarca($fecha, $horario['entrada'], $deltaEntrada, 'E');
        $marcaciones[] = $this->buildMarca($fecha, $horario['salida'], $deltaSalida, 'S');
    }

    /**
     * Entrada puntual y salida anticipada (10-25 min antes).
     *
     * @param  array<int, array<string, mixed>>  $marcaciones
     * @param  array{entrada: string, salida: string}  $horario
     */
    private function agregarMarcasSalidaTemprana(array &$marcaciones, Carbon $fecha, array $horario): void
    {
        $deltaEntrada = rand(-3, 5);
        $deltaSalida = rand(-25, -10);

        $marcaciones[] = $this->buildMarca($fecha, $horario['entrada'], $deltaEntrada, 'E');
        $marcaciones[] = $this->buildMarca($fecha, $horario['salida'], $deltaSalida, 'S');
    }

    /**
     * Solo marca entrada (sin salida).
     *
     * @param  array<int, array<string, mixed>>  $marcaciones
     * @param  array{entrada: string, salida: string}  $horario
     */
    private function agregarSoloEntrada(array &$marcaciones, Carbon $fecha, array $horario): void
    {
        $deltaEntrada = rand(-3, 15);

        $marcaciones[] = $this->buildMarca($fecha, $horario['entrada'], $deltaEntrada, 'E');
    }

    /**
     * Construye el array de una marcación individual.
     *
     * @return array<string, mixed>
     */
    private function buildMarca(Carbon $fecha, string $horaBase, int $deltaMinutos, string $tipo): array
    {
        $fechaMarcacion = $fecha->copy()
            ->setTimeFromTimeString($horaBase)
            ->addMinutes($deltaMinutos);

        $now = Carbon::now();

        return [
            'trabajador_id' => self::TRABAJADOR_ID,
            'altaTrabajador_id' => self::ALTA_ID,
            'localMarcacion_id' => null,
            'codigo' => 'MAR'.strtoupper(substr(md5(uniqid('', true)), 0, 7)),
            'fechaMarcacion' => $fechaMarcacion->toDateTimeString(),
            'fechaRegistro' => $fechaMarcacion->toDateTimeString(),
            'reloj_id' => null,
            'tipo' => $tipo,
            'medioMarcacion' => 'M', // M = Móvil
            'procesado' => true,
            'dispositivoMarca_id' => null,
            'utm_huso' => null,
            'utm_base' => null,
            'utm_x_este' => null,
            'utm_y_norte' => null,
            'created_by' => 1,
            'created_at' => $now->toDateTimeString(),
        ];
    }
}
