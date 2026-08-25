<?php

namespace App\Services\Configuracion;

use App\DTOs\Configuracion\CreateCargoDTO;
use App\DTOs\Configuracion\UpdateCargoDTO;
use App\Models\Cargos;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CargoService
{
    /**
     * @return LengthAwarePaginator<Cargos>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Cargos::query()
            ->with('rolTrabajador')
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%")
                    ->orWhere('codigo', 'ilike', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function crear(CreateCargoDTO $dto): Cargos
    {
        $cargo = Cargos::create($dto->toArray());
        Cache::forget('param.cargos');

        return $cargo;
    }

    public function actualizar(Cargos $cargo, UpdateCargoDTO $dto): bool
    {
        $result = $cargo->update($dto->toArray());
        Cache::forget('param.cargos');

        return $result;
    }

    public function eliminar(Cargos $cargo): bool
    {
        $result = $cargo->update(['activo' => false]);
        Cache::forget('param.cargos');

        return $result;
    }
}
