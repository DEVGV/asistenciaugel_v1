<?php

namespace App\Services\Entidad;

use App\DTOs\Entidad\CreateEntidadDTO;
use App\DTOs\Entidad\UpdateEntidadDTO;
use App\Models\Entidades;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EntidadService
{
    /**
     * @return LengthAwarePaginator<Entidades>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Entidades::query()
            ->with(['tipoEntidad'])
            ->when($request->search, function ($query, $search) {
                $query->where('razonSocial', 'ilike', "%{$search}%")
                    ->orWhere('ruc', 'like', "%{$search}%")
                    ->orWhere('razonComercial', 'ilike', "%{$search}%");
            })
            ->where('activo', true)
            ->orderBy('razonSocial')
            ->paginate(15);
    }

    public function crear(CreateEntidadDTO $dto): Entidades
    {
        return Entidades::create($dto->toArray());
    }

    public function actualizar(Entidades $entidad, UpdateEntidadDTO $dto): bool
    {
        return $entidad->update($dto->toArray());
    }

    public function eliminar(Entidades $entidad): bool
    {
        return $entidad->update(['activo' => false]);
    }
}
