<?php

namespace App\Services\Trabajador;

use App\DTOs\Trabajador\CreateTrabajadorDTO;
use App\DTOs\Trabajador\UpdateTrabajadorDTO;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TrabajadorService
{
    /**
     * @return LengthAwarePaginator<Trabajador>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Trabajador::query()
            ->with(['persona.tipoDocIdentidad', 'persona.sexo'])
            ->when($request->search, function ($query, $search) {
                $query->where('codigo', 'like', "%{$search}%")
                    ->orWhereHas('persona', function ($q) use ($search) {
                        $q->where('docIdentidad', 'like', "%{$search}%")
                            ->orWhere('paterno', 'ilike', "%{$search}%")
                            ->orWhere('materno', 'ilike', "%{$search}%")
                            ->orWhere('nombre', 'ilike', "%{$search}%");
                    });
            })
            ->where('activo', true)
            ->orderByDesc('id')
            ->paginate(15);
    }

    /**
     * Obtiene un trabajador con su persona y relaciones completas.
     */
    public function obtenerConRelaciones(Trabajador $trabajador): Trabajador
    {
        return $trabajador->load([
            'persona.tipoDocIdentidad',
            'persona.sexo',
            'persona.pais',
            'persona.telefonos.operador',
            'persona.emails',
            'persona.domicilios.zona',
        ]);
    }

    public function crear(CreateTrabajadorDTO $dto): Trabajador
    {
        return Trabajador::create($dto->toArray());
    }

    /**
     * @param  CreateTrabajadorDTO[]  $dtos
     */
    public function crearMasivo(array $dtos): void
    {
        DB::transaction(function () use ($dtos) {
            foreach ($dtos as $dto) {
                $this->crear($dto);
            }
        });
    }

    public function actualizar(Trabajador $trabajador, UpdateTrabajadorDTO $dto): bool
    {
        return $trabajador->update($dto->toArray());
    }

    public function eliminar(Trabajador $trabajador): bool
    {
        return $trabajador->update(['activo' => false]);
    }
}
