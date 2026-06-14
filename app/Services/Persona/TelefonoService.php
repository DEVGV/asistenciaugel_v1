<?php

namespace App\Services\Persona;

use App\Models\Personas;
use App\Models\Telefonos;
use Illuminate\Support\Collection;

class TelefonoService
{
    /**
     * @return Collection<int, Telefonos>
     */
    public function listarPorPersona(Personas $persona): Collection
    {
        return $persona->telefonos()->with('operador')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(Personas $persona, array $data): Telefonos
    {
        $data['persona_id'] = $persona->id;
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
