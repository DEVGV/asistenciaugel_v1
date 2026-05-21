<?php

namespace App\Services\Persona;

use App\DTOs\Persona\CreatePersonaDTO;
use App\DTOs\Persona\UpdatePersonaDTO;
use App\Models\Personas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonaService
{
    /**
     * @return LengthAwarePaginator<Personas>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Personas::query()
            ->with(['tipoDocIdentidad', 'sexo'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('docIdentidad', 'like', "%{$search}%")
                        ->orWhere('paterno', 'ilike', "%{$search}%")
                        ->orWhere('materno', 'ilike', "%{$search}%")
                        ->orWhere('nombre', 'ilike', "%{$search}%");
                });
            })
            ->when($request->boolean('exclude_trabajadores'), function ($query) {
                $query->whereDoesntHave('trabajador', function ($q) {
                    $q->where('activo', true);
                });
            })
            ->where('activo', true)
            ->orderBy('paterno')
            ->orderBy('materno')
            ->paginate(15);
    }

    /**
     * Obtiene una persona con todas sus relaciones (sub-recursos).
     */
    public function obtenerConRelaciones(Personas $persona): Personas
    {
        return $persona->load([
            'tipoDocIdentidad',
            'sexo',
            'pais',
            'telefonos.operador',
            'emails',
            'domicilios.zona',
        ]);
    }

    public function crear(CreatePersonaDTO $dto): Personas
    {
        return Personas::create($dto->toArray());
    }

    public function actualizar(Personas $persona, UpdatePersonaDTO $dto): bool
    {
        return $persona->update($dto->toArray());
    }

    public function eliminar(Personas $persona): bool
    {
        return $persona->update(['activo' => false]);
    }
}
