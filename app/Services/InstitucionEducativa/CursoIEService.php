<?php

namespace App\Services\InstitucionEducativa;

use App\Models\CursosIE;
use App\Models\InstitucionesEduc;
use Illuminate\Database\Eloquent\Collection;

class CursoIEService
{
    /**
     * @return Collection<int, CursosIE>
     */
    public function listarPorInstitucion(InstitucionesEduc $ie): Collection
    {
        return $ie->cursos()->orderBy('nombre')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(InstitucionesEduc $ie, array $data): CursosIE
    {
        return $ie->cursos()->create([
            'nombre' => $data['nombre'],
            'sigla' => $data['sigla'] ?? null,
            'created_by' => auth()->id() ?? 1,
            'activo' => $data['activo'] ?? true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(CursosIE $curso, array $data): bool
    {
        return $curso->update([
            'nombre' => $data['nombre'],
            'sigla' => $data['sigla'] ?? $curso->sigla,
            'activo' => $data['activo'] ?? $curso->activo,
        ]);
    }

    public function eliminar(CursosIE $curso): bool
    {
        return $curso->update(['activo' => false]);
    }
}
