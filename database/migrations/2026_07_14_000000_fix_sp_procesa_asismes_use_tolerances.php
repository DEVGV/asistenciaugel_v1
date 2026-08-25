<?php

use Illuminate\Database\Migrations\Migration;

/**
 * MIGRACIÓN VACÍA — DESCARTADA POR DECISIÓN DE PRODUCTO
 *
 * Se decidió NO modificar el procedimiento almacenado f_procesa_asismes_v1.
 * En su lugar, el rango horario (entHoraInicio/salHoraFin) se configura
 * amplio desde el modal "Configurar tolerancias" del frontend.
 *
 * Se conserva el archivo para no romper el histórico de migraciones.
 */
return new class extends Migration
{
    public function up(): void
    {
        // no-op
    }

    public function down(): void
    {
        // no-op
    }
};
