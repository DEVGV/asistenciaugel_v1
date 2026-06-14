<?php

namespace App\Services\Trabajador;

use App\DTOs\Trabajador\CreateTrabajadorDTO;
use App\DTOs\Trabajador\UpdateTrabajadorDTO;
use App\Models\Auth\Perfil;
use App\Models\Trabajador;
use App\Services\Auth\ContextoService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TrabajadorService
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    /**
     * @return LengthAwarePaginator<Trabajador>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return $this->contextoService->filtrarPorRelacionIe(Trabajador::query(), 'altas')
            ->with(['persona.tipoDocIdentidad', 'persona.sexo'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('codigo', 'like', "%{$search}%")
                        ->orWhereHas('persona', function ($qp) use ($search) {
                            $qp->where('docIdentidad', 'like', "%{$search}%")
                                ->orWhere('paterno', 'ilike', "%{$search}%")
                                ->orWhere('materno', 'ilike', "%{$search}%")
                                ->orWhere('nombre', 'ilike', "%{$search}%");
                        });
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
            'altas.institucionEducativa',
            'altas.condicionLaboral',
            'altas.tipoContrato',
            'altas.rolTrabajador',
            'altas.situacionLaboral',
            'altas.area',
            'altas.cargo',
            'altas.motivoBaja',
        ]);
    }

    /**
     * Carga las relaciones mínimas necesarias para el formulario de edición.
     */
    public function obtenerParaEdicion(Trabajador $trabajador): Trabajador
    {
        return $trabajador->load(['persona.tipoDocIdentidad', 'persona.sexo']);
    }

    /**
     * Búsqueda de trabajadores para selects/typeaheads.
     * Filtra opcionalmente por IE activa y término de búsqueda.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Trabajador>
     */
    public function buscarParaAsignacion(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->contextoService->filtrarPorRelacionIe(Trabajador::query(), 'altas')
            ->with(['persona', 'altas' => function ($q) {
                $q->whereNull('fechaBaja')->with(['cargo', 'institucionEducativa']);
            }])
            ->where('activo', true);

        if ($request->filled('ie_id')) {
            $ieId = $request->integer('ie_id');
            $query->whereHas('altas', function ($q) use ($ieId) {
                $q->where('institucionEducativa_id', $ieId)->whereNull('fechaBaja');
            });
        }

        if ($request->filled('search')) {
            $term = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($term) {
                $q->where('codigo', 'ilike', $term)
                    ->orWhereHas('persona', function ($qp) use ($term) {
                        $qp->where('docIdentidad', 'like', $term)
                            ->orWhere('paterno', 'ilike', $term)
                            ->orWhere('materno', 'ilike', $term)
                            ->orWhere('nombre', 'ilike', $term);
                    });
            });
        }

        return $query->orderByDesc('id')->take(20)->get();
    }

    /**
     * Lista perfiles activos para selects del formulario.
     *
     * @return Collection<int, Perfil>
     */
    public function listarPerfilesActivos(): Collection
    {
        return Perfil::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'descripcion']);
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
