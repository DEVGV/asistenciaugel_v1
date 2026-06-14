<?php

namespace App\Services\DiasNoLaborables;

use App\DTOs\DiasNoLaborables\CreateDiasNoLaborablesDTO;
use App\DTOs\DiasNoLaborables\UpdateDiasNoLaborablesDTO;
use App\Models\Conasis\ConasisDiasNoLaborables;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamFeriados;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DiasNoLaborablesService
{
    /**
     * Lista todos los días no laborables de una IE, ordenados por fecha.
     *
     * @return Collection<int, ConasisDiasNoLaborables>
     */
    public function listarPorInstitucion(InstitucionesEduc $institucion): Collection
    {
        return ConasisDiasNoLaborables::query()
            ->with(['feriado'])
            ->where('institucionEduc_id', $institucion->id)
            ->where('activo', true)
            ->orderBy('fecha')
            ->get();
    }

    public function crear(CreateDiasNoLaborablesDTO $dto): ConasisDiasNoLaborables
    {
        return ConasisDiasNoLaborables::create(
            $dto->toArray() + ['activo' => true, 'created_by' => auth()->id()]
        );
    }

    public function actualizar(ConasisDiasNoLaborables $dia, UpdateDiasNoLaborablesDTO $dto): ConasisDiasNoLaborables
    {
        $dia->update($dto->toArray());

        return $dia->fresh(['feriado']);
    }

    public function eliminar(ConasisDiasNoLaborables $dia): void
    {
        $dia->update(['activo' => false]);
    }

    /**
     * Genera los feriados por defecto (programaDefault = true) de t00_feriados
     * para el año indicado y los inserta en la IE, evitando duplicados por fecha.
     *
     * @return int Cantidad de días generados
     */
    public function generarFeriadosPorDefecto(InstitucionesEduc $institucion, int $anio): int
    {
        $feriados = ParamFeriados::query()
            ->where('programaDefault', true)
            ->where('activo', true)
            ->get();

        $generados = 0;

        DB::transaction(function () use ($feriados, $institucion, $anio, &$generados) {
            foreach ($feriados as $feriado) {
                if (! $feriado->diaMesDefault) {
                    continue;
                }

                // diaMesDefault tiene formato "DD/MM" o "DD-MM"
                $partes = preg_split('/[\/\-]/', $feriado->diaMesDefault);
                if (count($partes) !== 2) {
                    continue;
                }

                [$dia, $mes] = $partes;
                $fecha = sprintf('%04d-%02d-%02d', $anio, (int) $mes, (int) $dia);

                // Evitar duplicado en misma IE + fecha
                $existe = ConasisDiasNoLaborables::query()
                    ->where('institucionEduc_id', $institucion->id)
                    ->where('fecha', $fecha)
                    ->where('activo', true)
                    ->exists();

                if ($existe) {
                    continue;
                }

                ConasisDiasNoLaborables::create([
                    'institucionEduc_id' => $institucion->id,
                    'feriado_id'         => $feriado->id,
                    'fecha'              => $fecha,
                    'observacion'        => $feriado->descripcion,
                    'nacionalLocal'      => 'N',
                    'recuperable'        => 'N',
                    'activo'             => true,
                    'created_by'         => auth()->id(),
                ]);

                $generados++;
            }
        });

        return $generados;
    }
}
