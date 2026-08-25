<?php

namespace App\Services\Configuracion;

use App\DTOs\Configuracion\CreateAreaDTO;
use App\DTOs\Configuracion\UpdateAreaDTO;
use App\Models\Areas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

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
        $area = Areas::create($dto->toArray());
        Cache::forget('param.areas');

        return $area;
    }

    public function actualizar(Areas $area, UpdateAreaDTO $dto): bool
    {
        $result = $area->update($dto->toArray());
        Cache::forget('param.areas');

        return $result;
    }

    public function eliminar(Areas $area): bool
    {
        $result = $area->update(['activo' => false]);
        Cache::forget('param.areas');

        return $result;
    }
}
