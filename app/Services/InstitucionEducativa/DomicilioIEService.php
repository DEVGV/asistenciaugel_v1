<?php

namespace App\Services\InstitucionEducativa;

use App\Models\Domicilios;
use App\Models\InstitucionesEduc;
use Illuminate\Support\Collection;

class DomicilioIEService
{
    /**
     * @return Collection<int, Domicilios>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie): Collection
    {
        return $ie->domicilios()->with('zona')->orderBy('fechaInicio')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(InstitucionesEduc $ie, array $data): Domicilios
    {
        $data['institucionEduc_id'] = $ie->id;
        $data['created_by'] = auth()->id() ?? 1;
        $data['fechaInicio'] ??= now()->toDateString();

        return Domicilios::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(Domicilios $domicilio, array $data): bool
    {
        return $domicilio->update($data);
    }

    public function darDeBaja(Domicilios $domicilio): bool
    {
        return $domicilio->update(['fechaFin' => now()->toDateString()]);
    }

    public function eliminar(Domicilios $domicilio): bool
    {
        return $domicilio->delete();
    }
}
