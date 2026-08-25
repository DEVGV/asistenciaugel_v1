<?php

namespace App\Services\InstitucionEducativa;

use App\Models\Emails;
use App\Models\InstitucionesEduc;
use Illuminate\Support\Collection;

class EmailIEService
{
    /**
     * @return Collection<int, Emails>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie): Collection
    {
        return $ie->emails()->orderBy('fechaInicio')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(InstitucionesEduc $ie, array $data): Emails
    {
        $data['institucionEduc_id'] = $ie->id;
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
