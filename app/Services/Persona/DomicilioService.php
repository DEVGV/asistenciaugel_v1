<?php

namespace App\Services\Persona;

use App\Models\Domicilios;
use App\Models\Personas;
use Illuminate\Support\Collection;

class DomicilioService
{
    /**
     * @return Collection<int, Domicilios>
     */
    public function listarPorPersona(Personas $persona): Collection
    {
        return $persona->domicilios()->with('zona')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(Personas $persona, array $data): Domicilios
    {
        $data['persona_id'] = $persona->id;
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
