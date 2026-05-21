<?php

namespace App\Services\Configuracion;

use App\DTOs\Configuracion\CreateAreaDTO;
use App\DTOs\Configuracion\UpdateAreaDTO;
use App\Models\Areas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AreaService
{
    /**
     * @return LengthAwarePaginator<Areas>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Areas::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%")
                    ->orWhere('codigo', 'ilike', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function crear(CreateAreaDTO $dto): Areas
    {
        return Areas::create($dto->toArray());
    }

    public function actualizar(Areas $area, UpdateAreaDTO $dto): bool
    {
        return $area->update($dto->toArray());
    }

    public function eliminar(Areas $area): bool
    {
        return $area->update(['activo' => false]);
    }
}
