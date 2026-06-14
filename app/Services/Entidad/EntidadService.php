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

    /**
     * Búsqueda de entidades para selects/typeaheads.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Entidades>
     */
    public function buscarParaSelect(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        $tipoId     = $request->integer('tipo_entidad_id') ?: null;
        $tipoCodigo = $request->string('tipo_entidad_codigo')->trim() ?: null;
        $selectedId = $request->integer('selected_id') ?: null;
        $search     = $request->string('q')->trim();

        $query = Entidades::query()->select('id', 'razonSocial as nombre', 'ruc as codigo');

        if ($tipoId) {
            $query->where('tipoEntidad_id', $tipoId);
        } elseif ($tipoCodigo) {
            $query->whereHas('tipoEntidad', function ($q) use ($tipoCodigo) {
                $q->where('codigo', $tipoCodigo)
                  ->orWhere('abreviatura', $tipoCodigo);
            });
        }

        $query->where(function ($q) use ($selectedId) {
            $q->where('activo', true);
            if ($selectedId) {
                $q->orWhere('id', $selectedId);
            }
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('razonSocial', 'ilike', "%{$search}%")
                    ->orWhere('ruc', 'like', "%{$search}%");
            });
        } elseif ($selectedId) {
            $query->orderByRaw('CASE WHEN id = ? THEN 1 ELSE 0 END DESC', [$selectedId]);
        }

        return $query->limit(50)->get();
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
