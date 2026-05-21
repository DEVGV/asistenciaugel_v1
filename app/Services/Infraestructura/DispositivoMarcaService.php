<?php

namespace App\Services\Infraestructura;

use App\DTOs\Infraestructura\CreateDispositivoMarcaDTO;
use App\Models\Conasis\ConasisDispositivosMarca;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DispositivoMarcaService
{
    /**
     * @return LengthAwarePaginator<ConasisDispositivosMarca>
     */
    public function listarPaginado(Request $request): LengthAwarePaginator
    {
        return ConasisDispositivosMarca::query()
            ->with(['telefonoMovil'])
            ->orderByDesc('id')
            ->paginate(15);
    }

    public function crear(CreateDispositivoMarcaDTO $dto): ConasisDispositivosMarca
    {
        return ConasisDispositivosMarca::create($dto->toArray());
    }

    public function eliminar(ConasisDispositivosMarca $dispositivo): bool
    {
        return $dispositivo->delete();
    }
}
