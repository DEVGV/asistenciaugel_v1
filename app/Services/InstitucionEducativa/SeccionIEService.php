<?php

namespace App\Services\InstitucionEducativa;

use App\Models\GradosIE;
use App\Models\SeccionesIE;
use Illuminate\Database\Eloquent\Collection;

class SeccionIEService
{
    /**
     * @return Collection<int, SeccionesIE>
     */
    public function listarPorGrado(GradosIE $grado): Collection
    {
        return $grado->secciones()->orderBy('nombre')->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(GradosIE $grado, array $data): SeccionesIE
    {
        return $grado->secciones()->create([
            'nombre' => $data['nombre'],
            'sigla' => $data['sigla'] ?? null,
            'created_by' => auth()->id() ?? 1,
            'activo' => $data['activo'] ?? true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(SeccionesIE $seccion, array $data): bool
    {
        return $seccion->update([
            'nombre' => $data['nombre'],
            'sigla' => $data['sigla'] ?? $seccion->sigla,
            'activo' => $data['activo'] ?? $seccion->activo,
        ]);
    }

    public function eliminar(SeccionesIE $seccion): bool
    {
        return $seccion->update(['activo' => false]);
    }

    /**
     * Resuelve el institucionEduc_id navegando la relación grado → IE.
     * Carga la relación si no está en memoria para evitar queries adicionales en el controller.
     */
    public function obtenerIeId(SeccionesIE $seccion): ?int
    {
        return $seccion->grado?->institucionEduc_id
            ?? $seccion->load('grado')->grado?->institucionEduc_id;
    }
}
