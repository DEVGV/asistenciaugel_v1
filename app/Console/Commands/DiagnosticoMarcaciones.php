<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Diagnóstico del procedimiento f_procesa_asismes_v1: por qué toda la
 * asistencia queda como FALTA aunque el trabajador tenga marcaciones.
 *
 * Uso:
 *   php artisan diagnostico:marcaciones 1 --dni=76955021 \
 *       --fecha-desde=2026-05-01 --fecha-hasta=2026-05-31
 */
class DiagnosticoMarcaciones extends Command
{
    protected $signature = 'diagnostico:marcaciones {ie_id}
        {--dni= : DNI del trabajador a diagnosticar}
        {--trabajador= : ID del trabajador (alternativa al DNI)}
        {--fecha-desde=}
        {--fecha-hasta=}';

    protected $description = 'Explica por qué f_procesa_asismes_v1 marca FALTA aunque haya marcaciones';

    public function handle(): int
    {
        $ieId = (int) $this->argument('ie_id');
        $fechaDesde = $this->option('fecha-desde') ?? now()->startOfMonth()->toDateString();
        $fechaHasta = $this->option('fecha-hasta') ?? now()->endOfMonth()->toDateString();

        // 1. Resolver trabajador
        $trabajadorId = (int) $this->option('trabajador');
        if (! $trabajadorId && $this->option('dni')) {
            $row = DB::selectOne(
                'SELECT t.id FROM public.t_trabajador t
                 JOIN public.t_personas p ON p.id = t.persona_id
                 WHERE p."docIdentidad" = ?',
                [$this->option('dni')]
            );
            $trabajadorId = (int) ($row->id ?? 0);
        }

        if (! $trabajadorId) {
            $this->error('Debes indicar --dni=<dni> o --trabajador=<id>');
            return 1;
        }

        $this->info("═══════════════════════════════════════════════════════");
        $this->info("Diagnóstico  |  IE={$ieId}  Trabajador={$trabajadorId}  {$fechaDesde} → {$fechaHasta}");
        $this->info("═══════════════════════════════════════════════════════");

        // 2. Marcaciones en el periodo
        $marcas = DB::select(
            'SELECT id, "fechaMarcacion", tipo, "localMarcacion_id", reloj_id, procesado
             FROM conasis.t_marcaciones
             WHERE trabajador_id = ?
               AND date("fechaMarcacion") BETWEEN ?::date AND ?::date
             ORDER BY "fechaMarcacion"',
            [$trabajadorId, $fechaDesde, $fechaHasta]
        );

        $this->newLine();
        $this->info("1) MARCACIONES en el periodo: " . count($marcas));
        if (empty($marcas)) {
            $this->error('   ⚠ No hay marcaciones. Revisa la carga de Excel / reloj.');
            return 0;
        }
        $tiposDistintos = array_unique(array_map(fn ($m) => $m->tipo, $marcas));
        $this->line("   tipos encontrados: " . implode(',', $tiposDistintos) . " (la función solo usa 'A')");
        $localesEnMarcas = array_unique(array_map(fn ($m) => $m->localMarcacion_id, $marcas));
        $this->line("   localMarcacion_id distintos en las marcas: " . implode(',', $localesEnMarcas));

        // 3. Locales asignados al trabajador
        $locales = DB::select(
            'SELECT lm.id, lm."localInstEduc_id", lm."fechaInicio", lm."fechaFin",
                    lie."institucionEduc_id"
             FROM conasis."t_localesMarcacion" lm
             JOIN conasis."t_localesInstEduc" lie ON lie.id = lm."localInstEduc_id"
             WHERE lm.trabajador_id = ?',
            [$trabajadorId]
        );
        $this->newLine();
        $this->info("2) LOCALES DE MARCACIÓN asignados al trabajador: " . count($locales));
        foreach ($locales as $l) {
            $this->line("   lm.id={$l->id}  localInstEduc={$l->localInstEduc_id}  IE={$l->institucionEduc_id}  desde={$l->fechaInicio}  hasta=" . ($l->fechaFin ?? 'NULL'));
        }
        $idsAsignados = array_map(fn ($l) => $l->id, $locales);
        $huerfanas = array_diff($localesEnMarcas, $idsAsignados);
        if ($huerfanas) {
            $this->error('   ✗ CAUSA PROBABLE: las marcaciones apuntan a lm.id=' . implode(',', $huerfanas)
                . ' que NO está en t_localesMarcacion del trabajador → la función las ignora.');
        }

        // 4. Detalle de horarios y banderas críticas
        $detalles = DB::select(
            'SELECT ht.id AS horario_id, dh.turno_id, dh."nroTurno", dh."diaSemana", dh."nroDia",
                    dh."entHoraInicio", dh."entHoraFin", dh."salHoraInicio", dh."salHoraFin",
                    dh."entDiaInicio", dh."salDiaFin", dh.aplicar
             FROM conasis."t_horariosTrabajador" ht
             JOIN conasis."t_detalleHorarios" dh ON dh."horarioTrabajador_id" = ht.id
             WHERE ht."institucionEduc_id" = ?
               AND ht.trabajador_id = ?
               AND ht."fechaInicio" <= ?::date
               AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= ?::date)
             ORDER BY dh."nroTurno", dh."nroDia"',
            [$ieId, $trabajadorId, $fechaHasta, $fechaDesde]
        );
        $this->newLine();
        $this->info("3) DETALLE DE HORARIOS vigentes: " . count($detalles));
        $countAplicarFalse = 0;
        $countNocturno = 0;
        foreach ($detalles as $d) {
            $alertas = [];
            if ($d->aplicar !== true && $d->aplicar !== 't' && $d->aplicar !== 1) {
                $alertas[] = '✗ aplicar='.var_export($d->aplicar, true);
                $countAplicarFalse++;
            }
            if ($d->entDiaInicio != $d->salDiaFin) {
                $alertas[] = '✗ turno partido/nocturno';
                $countNocturno++;
            }
            $this->line("   turno={$d->turno_id}/{$d->nroTurno}  dia={$d->diaSemana}/{$d->nroDia}  {$d->entHoraInicio}→{$d->salHoraFin}  " . implode(' ', $alertas));
        }
        if ($countAplicarFalse) {
            $this->error("   ✗ {$countAplicarFalse} filas con aplicar<>TRUE → esas nunca cazan marcas.");
        }
        if ($countNocturno) {
            $this->error("   ✗ {$countNocturno} filas con entDiaInicio<>salDiaFin → la función las descarta.");
        }

        // 5. Simular WHERE completo del procedimiento
        $pass = DB::selectOne(
            'SELECT COUNT(*) AS n
             FROM conasis.t_marcaciones ma
             JOIN conasis."t_localesMarcacion" lm  ON lm.id = ma."localMarcacion_id" AND lm.trabajador_id = ma.trabajador_id
             JOIN conasis."t_localesInstEduc"  lie ON lie.id = lm."localInstEduc_id" AND lie."institucionEduc_id" = ?
             JOIN conasis."t_horariosTrabajador" ht ON ht.trabajador_id = ma.trabajador_id AND ht."institucionEduc_id" = ?
             JOIN conasis."t_detalleHorarios" dh ON dh."horarioTrabajador_id" = ht.id
             WHERE ma.trabajador_id = ?
               AND ma.tipo = \'A\'
               AND dh.aplicar = TRUE
               AND dh."entDiaInicio" = dh."salDiaFin"
               AND date(ma."fechaMarcacion") BETWEEN ?::date AND ?::date
               AND ht."fechaInicio" <= ?::date AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= ?::date)
               AND lm."fechaInicio" <= ?::date AND (lm."fechaFin" IS NULL OR lm."fechaFin" >= ?::date)
               AND (
                    (dh."diaSemana"=\'S\' AND date_part(\'dow\', ma."fechaMarcacion") = dh."nroDia")
                 OR (dh."diaSemana"=\'D\' AND extract(day from ma."fechaMarcacion") = dh."nroDia")
               )
               AND "time"(ma."fechaMarcacion") >= dh."entHoraInicio"
               AND "time"(ma."fechaMarcacion") <= dh."salHoraFin"',
            [$ieId, $ieId, $trabajadorId, $fechaDesde, $fechaHasta, $fechaHasta, $fechaDesde, $fechaHasta, $fechaDesde]
        );

        $this->newLine();
        $this->info("4) Marcaciones que PASAN el WHERE completo del procedimiento: " . ($pass->n ?? 0));
        if (($pass->n ?? 0) == 0) {
            $this->error('   ✗ 0 marcaciones sobreviven al filtro → por eso todo queda como FALTA.');
            $this->line('   Revisa los puntos 2 y 3 anteriores para saber qué condición está fallando.');
        } else {
            $this->info('   ✔ El filtro SÍ deja pasar marcaciones. Re-ejecuta el procesar mes.');
        }

        return 0;
    }
}
