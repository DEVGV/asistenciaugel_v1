<?php

namespace App\Services\Configuracion;

use App\DTOs\Configuracion\CreateCondicionLaboralDTO;
use App\DTOs\Configuracion\UpdateCondicionLaboralDTO;
use App\Models\CondicionesLaborales;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CondicionLaboralService
{
    /**
     * @return LengthAwarePaginator<CondicionesLaborales>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return CondicionesLaborales::query()
            ->with(['regimenLaboral', 'tipoTrabajador'])
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%")
                    ->orWhere('codigo', 'ilike', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function crear(CreateCondicionLaboralDTO $dto): CondicionesLaborales
    {
        return CondicionesLaborales::create($dto->toArray());
    }

    public function actualizar(CondicionesLaborales $condicion, UpdateCondicionLaboralDTO $dto): bool
    {
        return $condicion->update($dto->toArray());
    }

    public function eliminar(CondicionesLaborales $condicion): bool
    {
        return (bool) $condicion->delete();
    }
}
