<?php

namespace App\Services\InstitucionEducativa;

use App\DTOs\InstitucionEducativa\CreateInstEducDTO;
use App\DTOs\InstitucionEducativa\UpdateInstEducDTO;
use App\Models\InstitucionesEduc;
use App\Services\Auth\ContextoService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InstitucionEducativaService
{
    public function __construct(
        private ContextoService $contextoService,
    ) {}

    /**
     * @return LengthAwarePaginator<InstitucionesEduc>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return $this->contextoService->filtrarInstituciones(InstitucionesEduc::query())
            ->with(['regimenEduc', 'tipoInstEduc', 'modalidadFormativa', 'nivelCiclo', 'entidadUgel', 'entidadAdmin'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('codigoInstitucion', 'ilike', "%{$search}%")
                        ->orWhere('codigoModular', 'ilike', "%{$search}%")
                        ->orWhere('nombreLegal', 'ilike', "%{$search}%");
                });
            })
            ->orderBy('nombreLegal')
            ->paginate(15);
    }

    /**
     * Obtiene una IE con todas sus relaciones y sub-recursos.
     */
    public function obtenerConRelaciones(InstitucionesEduc $ie): InstitucionesEduc
    {
        return $ie->load([
            'regimenEduc',
            'tipoInstEduc',
            'modalidadFormativa',
            'nivelCiclo',
            'entidadUgel',
            'entidadAdmin',
            'cursos',
            'grados.secciones',
            'localesInstEduc.local.zona',
            'localesInstEduc.relojes',
            'localesInstEduc.localesMarcacion.trabajador.persona',
            'diasNoLaborables' => fn ($q) => $q->where('activo', true)->orderBy('fecha')->with('feriado'),
            'telefonos' => fn ($q) => $q->with('operador')->orderBy('fechaInicio'),
            'emails' => fn ($q) => $q->orderBy('fechaInicio'),
            'domicilios' => fn ($q) => $q->with('zona')->orderBy('fechaInicio'),
        ]);
    }

    /**
     * Búsqueda de IE para selects/typeaheads (paginada).
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator<InstitucionesEduc>
     */
    public function buscarParaSelect(Request $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = $this->contextoService->filtrarInstituciones(InstitucionesEduc::query())
            ->select(['id', 'nombreLegal', 'codigoInstitucion', 'codigoModular']);

        if ($request->filled('search')) {
            $term = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER("nombreLegal") LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER("codigoInstitucion") LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER("codigoModular") LIKE LOWER(?)', [$term]);
            });
        }

        $perPage = min((int) $request->get('per_page', 30), 100);

        return $query->orderBy('nombreLegal')->paginate($perPage);
    }

    public function crear(CreateInstEducDTO $dto): InstitucionesEduc
    {
        return InstitucionesEduc::create($dto->toArray());
    }

    public function actualizar(InstitucionesEduc $ie, UpdateInstEducDTO $dto): bool
    {
        $data = $dto->toArray();

        // Las fechas nunca se modifican desde el formulario de edición
        unset($data['fechaInicio'], $data['fechaFin']);

        return $ie->update($data);
    }

    public function eliminar(InstitucionesEduc $ie): bool
    {
        return $ie->delete();
    }
}
