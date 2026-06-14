<?php

namespace App\Services\Persona;

use App\DTOs\Persona\CreatePersonaDTO;
use App\DTOs\Persona\UpdatePersonaDTO;
use App\Models\Personas;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonaService
{
    /**
     * @return LengthAwarePaginator<Personas>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return Personas::query()
            ->with(['tipoDocIdentidad', 'sexo', 'trabajador'])
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
        return DB::transaction(function () use ($dto) {
            $persona = Personas::create($dto->toArray());

            if ($dto->es_trabajador) {
                $trabajador = Trabajador::create([
                    'persona_id' => $persona->id,
                    'created_by' => auth()->id() ?? 1,
                    'activo' => true,
                ]);

                User::create([
                    'trabajador_id' => $trabajador->id,
                    'login' => $persona->docIdentidad,
                    'password' => Hash::make($persona->docIdentidad),
                    'activo' => true,
                ]);
            }

            return $persona;
        });
    }

    public function actualizar(Personas $persona, UpdatePersonaDTO $dto): bool
    {
        return $persona->update($dto->toArray());
    }

    public function eliminar(Personas $persona): bool
    {
        return $persona->update(['activo' => false]);
    }

    /**
     * Convierte a una persona existente en trabajador (y le crea/activa usuario).
     */
    public function convertirEnTrabajador(Personas $persona): void
    {
        DB::transaction(function () use ($persona) {
            // 1. Activar o crear trabajador
            $persona->load('trabajador.user');
            $trabajador = $persona->trabajador;
            if ($trabajador) {
                $trabajador->update(['activo' => true]);
            } else {
                $trabajador = Trabajador::create([
                    'persona_id' => $persona->id,
                    'created_by' => auth()->id() ?? 1,
                    'activo' => true,
                ]);
            }

            // 2. Activar o crear usuario
            $user = $trabajador->user ?? null;
            if ($user) {
                $user->update(['activo' => true]);
            } else {
                User::create([
                    'trabajador_id' => $trabajador->id,
                    'login' => $persona->docIdentidad,
                    'password' => Hash::make($persona->docIdentidad),
                    'activo' => true,
                ]);
            }
        });
    }
}
