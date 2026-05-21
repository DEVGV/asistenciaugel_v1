<?php

namespace App\Services\Infraestructura;

use App\DTOs\Infraestructura\CreateRelojDTO;
use App\DTOs\Infraestructura\UpdateRelojDTO;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Conasis\ConasisRelojes;
use Illuminate\Database\Eloquent\Collection;

class RelojService
{
    /**
     * @return Collection<int, ConasisRelojes>
     */
    public function listarPorLocalInstEduc(ConasisLocalesInstEduc $localInstEduc): Collection
    {
        return $localInstEduc->relojes()
            ->orderBy('nombre')
            ->get();
    }

    public function crear(CreateRelojDTO $dto): ConasisRelojes
    {
        return ConasisRelojes::create($dto->toArray());
    }

    public function actualizar(ConasisRelojes $reloj, UpdateRelojDTO $dto): bool
    {
        return $reloj->update($dto->toArray());
    }

    public function eliminar(ConasisRelojes $reloj): bool
    {
        return $reloj->update(['activo' => false]);
    }
}
