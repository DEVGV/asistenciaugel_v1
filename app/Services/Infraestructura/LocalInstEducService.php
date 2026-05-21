<?php

namespace App\Services\Infraestructura;

use App\DTOs\Infraestructura\CreateLocalDTO;
use App\Models\Conasis\ConasisLocales;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\InstitucionesEduc;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LocalInstEducService
{
    /**
     * @return Collection<int, ConasisLocalesInstEduc>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie): Collection
    {
        return $ie->localesInstEduc()
            ->with(['local.zona', 'relojes', 'localesMarcacion.trabajador.persona'])
            ->get();
    }

    /**
     * Crea un local nuevo y lo asocia a la IE automáticamente.
     */
    public function crearYAsignar(CreateLocalDTO $localDTO, InstitucionesEduc $ie, ?string $fechaInicio = null, ?string $fechaFin = null): ConasisLocalesInstEduc
    {
        return DB::transaction(function () use ($localDTO, $ie, $fechaInicio, $fechaFin) {
            $local = ConasisLocales::create($localDTO->toArray());

            return ConasisLocalesInstEduc::create([
                'local_id' => $local->id,
                'entidad_id' => $ie->entidadUgel_id,
                'institucionEduc_id' => $ie->id,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'created_by' => auth()->id() ?? 1,
            ]);
        });
    }

    public function desasignar(ConasisLocalesInstEduc $localInstEduc): bool
    {
        return $localInstEduc->delete();
    }
}
