<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiagnosticoHorarios extends Command
{
    protected $signature = 'diagnostico:horarios {ie_id} {--trabajadores=} {--fecha-desde=} {--fecha-hasta=}';
    protected $description = 'Diagnostica por qué la función f_procesa_asismes_v1 retorna NO_HOR';

    public function handle(): int
    {
        $ieId = $this->argument('ie_id');
        $fechaDesde = $this->option('fecha-desde') ?? now()->startOfMonth()->toDateString();
        $fechaHasta = $this->option('fecha-hasta') ?? now()->endOfMonth()->toDateString();
        $trabajadoresOpt = $this->option('trabajadores');

        $this->info("=== DIAGNÓSTICO DE HORARIOS ===");
        $this->info("IE: {$ieId} | Período: {$fechaDesde} a {$fechaHasta}");
        $this->newLine();

        // 1. Verificar trabajadores activos (altas)
        $altasQuery = DB::table('t_altasTrabajadores as at')
            ->join('t_trabajador as t', 't.id', '=', 'at.trabajador_id')
            ->join('t_personas as p', 'p.id', '=', 't.persona_id')
            ->where('at.institucionEducativa_id', $ieId)
            ->whereNull('at.fechaBaja')
            ->where('at.fechaInicio', '<=', $fechaHasta)
            ->where(function ($q) use ($fechaDesde) {
                $q->whereNull('at.fechaFin')
                    ->orWhere('at.fechaFin', '>=', $fechaDesde);
            })
            ->select('at.trabajador_id', 'at.id as alta_id', 'p.paterno', 'p.materno', 'p.nombre', 'at.fechaInicio', 'at.fechaFin');

        if ($trabajadoresOpt) {
            $ids = explode(',', $trabajadoresOpt);
            $altasQuery->whereIn('at.trabajador_id', $ids);
        }

        $altas = $altasQuery->get();

        $this->info("1. TRABAJADORES ACTIVOS ENCONTRADOS: {$altas->count()}");
        foreach ($altas as $a) {
            $nombre = trim("{$a->paterno} {$a->materno}, {$a->nombre}");
            $this->line("   - Trabajador ID={$a->trabajador_id} | Alta ID={$a->alta_id} | {$nombre} | Inicio: {$a->fechaInicio} | Fin: " . ($a->fechaFin ?? 'NULL'));
        }
        $this->newLine();

        // 2. Verificar horarios en t_horariosTrabajador
        foreach ($altas as $a) {
            $nombre = trim("{$a->paterno} {$a->materno}, {$a->nombre}");
            $this->warn("--- Trabajador: {$nombre} (ID={$a->trabajador_id}) ---");

            // 2a. Todos los horarios del trabajador en esta IE (sin filtro de fecha)
            $todosHorarios = DB::select('
                SELECT ht.id, ht."institucionEduc_id", ht.trabajador_id,
                       ht."fechaInicio", ht."fechaFin", ht."altaTrabajador_id",
                       ht."tipoHorario"
                FROM conasis."t_horariosTrabajador" AS ht
                WHERE ht."institucionEduc_id" = ?
                  AND ht.trabajador_id = ?
                ORDER BY ht."fechaInicio"
            ', [$ieId, $a->trabajador_id]);

            $this->info("   2a. TODOS los horarios (sin filtro fecha): " . count($todosHorarios));
            foreach ($todosHorarios as $h) {
                $this->line("       Horario ID={$h->id} | Inicio: {$h->fechaInicio} | Fin: " . ($h->fechaFin ?? 'NULL') . " | Alta: {$h->altaTrabajador_id} | Tipo: {$h->tipoHorario}");
            }

            // 2b. Horarios que cumplen el filtro de fecha de la función
            $horariosValidos = DB::select('
                SELECT ht.id, ht."fechaInicio", ht."fechaFin"
                FROM conasis."t_horariosTrabajador" AS ht
                WHERE ht."institucionEduc_id" = ?
                  AND ht.trabajador_id = ?
                  AND ht."fechaInicio" <= ?::date
                  AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= ?::date)
                ORDER BY ht."fechaInicio"
            ', [$ieId, $a->trabajador_id, $fechaHasta, $fechaDesde]);

            $this->info("   2b. Horarios con fecha válida (inicio<={$fechaHasta} AND fin>='{$fechaDesde}' o NULL): " . count($horariosValidos));
            foreach ($horariosValidos as $h) {
                $this->line("       Horario ID={$h->id} | Inicio: {$h->fechaInicio} | Fin: " . ($h->fechaFin ?? 'NULL'));
            }

            // 2c. Detalle de horarios (JOIN con t_detalleHorarios) — esto es lo que realmente usa la función
            $conDetalle = DB::select('
                SELECT DISTINCT ht.id AS horario_id, dh.turno_id, dh."nroTurno", dh."diaSemana",
                       dh."nroDia", dh.aplicar,
                       ht."fechaInicio", ht."fechaFin"
                FROM conasis."t_horariosTrabajador" AS ht
                JOIN conasis."t_detalleHorarios" AS dh ON dh."horarioTrabajador_id" = ht.id
                WHERE ht."institucionEduc_id" = ?
                  AND ht.trabajador_id = ?
                  AND ht."fechaInicio" <= ?::date
                  AND (ht."fechaFin" IS NULL OR ht."fechaFin" >= ?::date)
                ORDER BY dh.turno_id, dh."nroTurno"
            ', [$ieId, $a->trabajador_id, $fechaHasta, $fechaDesde]);

            $this->info("   2c. Horarios CON detalle (JOIN t_detalleHorarios): " . count($conDetalle));
            foreach ($conDetalle as $d) {
                $this->line("       Horario ID={$d->horario_id} | Turno={$d->turno_id} | NroTurno={$d->nroTurno} | DiaSemana={$d->diaSemana} | NroDia={$d->nroDia} | Aplicar={$d->aplicar}");
            }

            if (count($horariosValidos) > 0 && count($conDetalle) === 0) {
                $this->error("   ⚠ PROBLEMA: Tiene horario pero NO tiene detalle de horarios vinculado.");

                // Ver si hay detalles sin filtro de fecha
                $detallesSinFiltro = DB::select('
                    SELECT dh.id, dh."horarioTrabajador_id", dh.turno_id, dh."nroTurno"
                    FROM conasis."t_detalleHorarios" AS dh
                    JOIN conasis."t_horariosTrabajador" AS ht ON ht.id = dh."horarioTrabajador_id"
                    WHERE ht."institucionEduc_id" = ?
                      AND ht.trabajador_id = ?
                    LIMIT 10
                ', [$ieId, $a->trabajador_id]);
                $this->line("       Detalles totales (sin filtro fecha): " . count($detallesSinFiltro));
            }

            if (count($todosHorarios) === 0) {
                $this->error("   ⚠ PROBLEMA: No tiene NINGÚN horario registrado en t_horariosTrabajador para esta IE.");
            } elseif (count($horariosValidos) === 0) {
                $this->error("   ⚠ PROBLEMA: Tiene horarios pero NINGUNO cubre el período {$fechaDesde} - {$fechaHasta}.");
            }

            $this->newLine();
        }

        return 0;
    }
}
