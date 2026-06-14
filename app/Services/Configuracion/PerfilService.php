<?php

namespace App\Services\Configuracion;

use App\Models\Auth\Perfil;
use App\Models\Auth\Permiso;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PerfilService
{
    /**
     * @return LengthAwarePaginator<Perfil>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Perfil::query()
            ->withCount('permisos')
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function listarTodos(): Collection
    {
        return Perfil::where('activo', true)->with('permisos')->orderBy('nombre')->get();
    }

    public function crear(string $nombre, ?string $descripcion): Perfil
    {
        return Perfil::create([
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'activo'      => true,
        ]);
    }

    public function actualizar(Perfil $perfil, string $nombre, ?string $descripcion, bool $activo): void
    {
        $perfil->update([
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'activo'      => $activo,
        ]);
    }

    public function eliminar(Perfil $perfil): void
    {
        $perfil->update(['activo' => false]);
    }

    /**
     * Sincroniza los permisos de un perfil reemplazando los existentes.
     */
    public function sincronizarPermisos(Perfil $perfil, array $permisoIds): void
    {
        $perfil->permisos()->sync($permisoIds);
    }

    /**
     * Devuelve todos los permisos agrupados por módulo.
     */
    public function permisosPorModulo(): Collection
    {
        return Permiso::where('activo', true)
            ->orderBy('modulo')
            ->orderBy('codigo')
            ->get()
            ->groupBy('modulo');
    }

    public function perfilConPermisos(Perfil $perfil): Perfil
    {
        return $perfil->load('permisos');
    }
}
