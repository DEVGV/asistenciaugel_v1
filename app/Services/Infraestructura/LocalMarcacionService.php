<?php

namespace App\Services\Infraestructura;

use App\DTOs\Infraestructura\CreateLocalMarcacionDTO;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Conasis\ConasisLocalesMarcacion;
use Illuminate\Database\Eloquent\Collection;

class LocalMarcacionService
{
    /**
     * @return Collection<int, ConasisLocalesMarcacion>
     */
    public function listarPorLocalInstEduc(ConasisLocalesInstEduc $localInstEduc): Collection
    {
        return $localInstEduc->localesMarcacion()
            ->with(['trabajador.persona'])
            ->get();
    }

    public function asignar(CreateLocalMarcacionDTO $dto): ConasisLocalesMarcacion
    {
        return ConasisLocalesMarcacion::create($dto->toArray());
    }

    public function desasignar(ConasisLocalesMarcacion $localMarcacion): bool
    {
        return $localMarcacion->delete();
    }
}
