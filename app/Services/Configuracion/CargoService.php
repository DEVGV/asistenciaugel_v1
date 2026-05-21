<?php

namespace App\Services\Configuracion;

use App\DTOs\Configuracion\CreateCargoDTO;
use App\DTOs\Configuracion\UpdateCargoDTO;
use App\Models\Cargos;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CargoService
{
    /**
     * @return LengthAwarePaginator<Cargos>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Cargos::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%")
                    ->orWhere('codigo', 'ilike', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function crear(CreateCargoDTO $dto): Cargos
    {
        return Cargos::create($dto->toArray());
    }

    public function actualizar(Cargos $cargo, UpdateCargoDTO $dto): bool
    {
        return $cargo->update($dto->toArray());
    }

    public function eliminar(Cargos $cargo): bool
    {
        return $cargo->update(['activo' => false]);
    }
}
