<?php

namespace App\Services\InstitucionEducativa;

use App\Models\InstitucionesEduc;
use App\Models\Telefonos;
use Illuminate\Support\Collection;

class TelefonoIEService
{
    /**
     * @return Collection<int, Telefonos>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie): Collection
    {
        return $ie->telefonos()->with('operador')->orderBy('fechaInicio')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(InstitucionesEduc $ie, array $data): Telefonos
    {
        $data['institucionEduc_id'] = $ie->id;
        $data['created_by'] = auth()->id() ?? 1;
        $data['fechaInicio'] ??= now()->toDateString();

        return Telefonos::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(Telefonos $telefono, array $data): bool
    {
        return $telefono->update($data);
    }

    public function darDeBaja(Telefonos $telefono): bool
    {
        return $telefono->update(['fechaFin' => now()->toDateString()]);
    }

    public function eliminar(Telefonos $telefono): bool
    {
        return $telefono->delete();
    }
}
