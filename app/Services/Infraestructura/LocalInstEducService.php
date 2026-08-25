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
     * Devuelve los locales de marcación disponibles para una IE,
     * en formato simple {id, nombre} para selects del formulario de altas.
     * El id corresponde al localInstEduc_id (no al local_id).
     *
     * @return array<int, array{id: int, nombre: string}>
     */
    public function opcionesParaSelect(InstitucionesEduc $ie): array
    {
        return $ie->localesInstEduc()
            ->with('local:id,nombre,domicilio')
            ->where(function ($q) {
                $q->whereNull('fechaFin')
                  ->orWhere('fechaFin', '>=', now()->toDateString());
            })
            ->get()
            ->map(fn (ConasisLocalesInstEduc $li) => [
                'id'     => $li->id,
                'nombre' => $li->local?->nombre
                    ?? $li->local?->domicilio
                    ?? "Local #{$li->local_id}",
            ])
            ->values()
            ->all();
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
