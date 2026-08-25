<?php

namespace App\Services\Configuracion;

use App\DTOs\Configuracion\CreateCondicionLaboralDTO;
use App\DTOs\Configuracion\UpdateCondicionLaboralDTO;
use App\Models\CondicionesLaborales;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

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
        $condicion = CondicionesLaborales::create($dto->toArray());
        Cache::forget('param.condiciones-laborales');

        return $condicion;
    }

    public function actualizar(CondicionesLaborales $condicion, UpdateCondicionLaboralDTO $dto): bool
    {
        $result = $condicion->update($dto->toArray());
        Cache::forget('param.condiciones-laborales');

        return $result;
    }

    public function eliminar(CondicionesLaborales $condicion): bool
    {
        Cache::forget('param.condiciones-laborales');

        return (bool) $condicion->delete();
    }
}
