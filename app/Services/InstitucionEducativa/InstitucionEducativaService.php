<?php

namespace App\Services\InstitucionEducativa;

use App\DTOs\InstitucionEducativa\CreateInstEducDTO;
use App\DTOs\InstitucionEducativa\UpdateInstEducDTO;
use App\Models\InstitucionesEduc;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InstitucionEducativaService
{
    /**
     * @return LengthAwarePaginator<InstitucionesEduc>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return InstitucionesEduc::query()
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
        ]);
    }

    public function crear(CreateInstEducDTO $dto): InstitucionesEduc
    {
        return InstitucionesEduc::create($dto->toArray());
    }

    public function actualizar(InstitucionesEduc $ie, UpdateInstEducDTO $dto): bool
    {
        return $ie->update($dto->toArray());
    }

    public function eliminar(InstitucionesEduc $ie): bool
    {
        return $ie->delete();
    }
}
