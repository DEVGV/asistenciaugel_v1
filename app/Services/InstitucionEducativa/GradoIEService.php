<?php

namespace App\Services\InstitucionEducativa;

use App\Models\GradosIE;
use App\Models\InstitucionesEduc;
use Illuminate\Database\Eloquent\Collection;

class GradoIEService
{
    /**
     * @return Collection<int, GradosIE>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie): Collection
    {
        return $ie->grados()->with('secciones')->orderBy('nombre')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(InstitucionesEduc $ie, array $data): GradosIE
    {
        return $ie->grados()->create([
            'nombre' => $data['nombre'],
            'sigla' => $data['sigla'] ?? null,
            'created_by' => auth()->id() ?? 1,
            'activo' => $data['activo'] ?? true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(GradosIE $grado, array $data): bool
    {
        return $grado->update([
            'nombre' => $data['nombre'],
            'sigla' => $data['sigla'] ?? $grado->sigla,
            'activo' => $data['activo'] ?? $grado->activo,
        ]);
    }

    public function eliminar(GradosIE $grado): bool
    {
        return $grado->update(['activo' => false]);
    }
}
