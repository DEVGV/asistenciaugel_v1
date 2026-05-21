<?php

namespace App\Services\Zona;

use App\DTOs\Zona\CreateZonaDTO;
use App\DTOs\Zona\UpdateZonaDTO;
use App\Models\Zonas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ZonaService
{
    /**
     * @return LengthAwarePaginator<Zonas>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Zonas::query()
            ->with(['tipoZona', 'distrito.provincia.departamento'])
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%")
                    ->orWhere('abreviatura', 'ilike', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function crear(CreateZonaDTO $dto): Zonas
    {
        return Zonas::create($dto->toArray() + ['created_by' => auth()->id()]);
    }

    public function actualizar(Zonas $zona, UpdateZonaDTO $dto): bool
    {
        return $zona->update($dto->toArray());
    }

    public function eliminar(Zonas $zona): bool
    {
        return $zona->update(['activo' => false]);
    }
}
