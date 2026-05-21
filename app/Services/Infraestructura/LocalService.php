<?php

namespace App\Services\Infraestructura;

use App\DTOs\Infraestructura\CreateLocalDTO;
use App\DTOs\Infraestructura\UpdateLocalDTO;
use App\Models\Conasis\ConasisLocales;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LocalService
{
    /**
     * @return LengthAwarePaginator<ConasisLocales>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return ConasisLocales::query()
            ->with(['zona'])
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'ilike', "%{$search}%")
                    ->orWhere('domicilio', 'ilike', "%{$search}%");
            })
            ->where('activo', true)
            ->orderByDesc('id')
            ->paginate(15);
    }

    public function crear(CreateLocalDTO $dto): ConasisLocales
    {
        return ConasisLocales::create($dto->toArray());
    }

    public function actualizar(ConasisLocales $local, UpdateLocalDTO $dto): bool
    {
        return $local->update($dto->toArray());
    }

    public function eliminar(ConasisLocales $local): bool
    {
        return $local->update(['activo' => false]);
    }
}
