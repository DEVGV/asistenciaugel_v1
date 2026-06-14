<?php

namespace App\Services\Persona;

use App\Models\Emails;
use App\Models\Personas;
use Illuminate\Support\Collection;

class EmailService
{
    /**
     * @return Collection<int, Emails>
     */
    public function listarPorPersona(Personas $persona): Collection
    {
        return $persona->emails()->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(Personas $persona, array $data): Emails
    {
        $data['persona_id'] = $persona->id;
        $data['created_by'] = auth()->id() ?? 1;
        $data['fechaInicio'] ??= now()->toDateString();

        return Emails::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(Emails $email, array $data): bool
    {
        return $email->update($data);
    }

    public function darDeBaja(Emails $email): bool
    {
        return $email->update(['fechaFin' => now()->toDateString()]);
    }

    public function eliminar(Emails $email): bool
    {
        return $email->delete();
    }
}
